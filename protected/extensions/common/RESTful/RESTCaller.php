<?php

class RESTException extends CException
{
	public $caller;
	
	function __construct(RESTCaller $caller)
	{
		$this->caller = $caller;
		parent::__construct($caller->getMessage(), $caller->getCode());
	}
}

/**
* Class này phục vụ việc gọi REST API một cách thuận tiện nhất
*
* @author Tarzan <hocdt85@gmail.com>
*
* @property string $url
* @property integer $code
* @property string $message
* @property string $header
* @property string $rawData
* @property mixed $data
*/
class RESTCaller extends CComponent
{
	const SIGNATURE_FIELD='signature';

	public $httpUsername;
	public $httpPassword;

	public $rsaPrivateKey;

	public $timeout = 10;

	protected $_url = array('', array());

	protected $_code;
	protected $_message;
	protected $_data;
	protected $_header;
	protected $_rawData;

	public $logModelClass = '';
	public $logRequest = false;

	function getUrl($args = array())
	{
		$args = array_merge($this->_url[1], $args);
		return $this->_url[0].'?'.http_build_query($args);
	}

	function setUrl($value)
	{
		if (is_array($value)) {
			assert('is_string($value[0])');

			$this->_url[0] = $value[0];

			array_shift($value);
			$this->_url[1] = $value;
			return;
		}

		assert('is_string($value)');
		$p = strpos($value, '?');
		if ($p === false) $this->_url = array($value, array());
		else {
			$this->_url[0] = substr($value, 0, $p);
			$value = substr($value, $p+1);
			if (false !== ($p = strpos('#', $value))) $value = substr($value, 0, $p+1);

			parse_str($value, $this->_url[1]);
		}

	}

	function getCode()
	{
		return $this->_code;
	}

	function getMessage()
	{
		return $this->_message;
	}

	function getHeader()
	{
		return $this->_header;
	}

	function getRawData()
	{
		return $this->_rawData;
	}

	function getData()
	{
		return $this->_data;
	}

	function receiveResponseHeader($curl, $headerStr)
	{
		$this->_header .= $headerStr;
		return strlen($headerStr);
	}

	protected function analyzeCodeAndMessage()
	{
		$headers = explode("\r\n\r\n", $this->getHeader());

		$i = count($headers)-1;
		while ($i>=0) {
			$header=trim($headers[$i--]);
			if (!empty($header)) break;
		}

		preg_match_all('|^\s*HTTP/\d+\.\d+\s+(\d+)\s*(.*\S)|', $header, $m);

		if (empty($m[0])) {
			$this->_code = 200;
			$this->_message = 'OK';
		} else {
			$this->_code = $m[1][0];
			$this->_message = $m[2][0];
		}
	}

	private function initCurl($method, $url, $data)
	{
		$curl = curl_init($url);
		assert('$curl !== FALSE');

		switch ($method) {
			case 'GET': curl_setopt($curl, CURLOPT_HTTPGET, true); break;
			case 'POST': curl_setopt($curl, CURLOPT_POST, true); break;
			case 'PUT': curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT'); break;
		}

		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER=>true,
			CURLOPT_HTTPAUTH=>CURLAUTH_DIGEST|CURLAUTH_BASIC,
			CURLOPT_USERPWD=>$this->httpUsername.':'.$this->httpPassword,
			CURLOPT_TIMEOUT=>$this->timeout,
			CURLOPT_SSL_VERIFYPEER=>true,
			CURLOPT_CAINFO=>dirname(__FILE__).'/ca.certs',
			CURLOPT_HEADERFUNCTION=>array($this, 'receiveResponseHeader'),
		));

		if (!empty($data)) curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data, '', '&'));

		return $curl;
	}

	protected function getServerAddrFromUrl($url)
	{
		$host = parse_url($url, PHP_URL_HOST);
		assert('$host !== false');
		return $host;
	}

	protected function saveLog($log)
	{
		if (!$log->save())
			Yii::log('Can not log RestApiCall'."\n".print_r($log->getErrors(), true), CLogger::LEVEL_ERROR, 'application.rest');
	}

	/**
	* @return RestApiLog
	*/
	protected function createRestApiLog()
	{
		assert('!empty($this->logModelClass)');

		$log = new $this->logModelClass();

		return $log;
	}

	/**
	* Chuẩn hóa dữ liệu với chữ ký để gọi lên hệ thống Bảo Kim
	*
	* @param string $method GET,POST, ...
	* @param array $args dữ liệu thêm vào query string
	* @param array $data dữ liệu post
	*/
	protected function normalizeUrlWithSignature($method, $args, $data)
	{
        if (empty($this->rsaPrivateKey)) return array($this->getUrl($args), $data);

        list($url,$get) = $this->_url;

        $args=array_merge($get, $args);

        ksort($args);
        ksort($data);
        $method=strtoupper($method);

        $str = $method.'&'.urlencode(parse_url($url, PHP_URL_PATH)).'&'.urlencode(http_build_query($args)).'&'.urlencode(http_build_query($data));
        $priKey = openssl_get_privatekey($this->rsaPrivateKey);
        assert('$priKey !== false');
        $x = openssl_sign($str, $signature, $priKey, OPENSSL_ALGO_SHA1);
        assert('$x !== false');

        $args[self::SIGNATURE_FIELD] = base64_encode($signature);

        $url = $url.'?'.http_build_query($args);

        return array($url, $data);
	}

	protected function doRequestWithoutLog($method, $args, $data)
	{
		list($url, $data) = $this->normalizeUrlWithSignature($method, $args, $data);
		$curl = $this->initCurl($method, $url, $data);

		$this->_header = '';
		$this->_rawData = curl_exec($curl);

		if ($this->_rawData === false) {
			try {
				throw new Exception(curl_error($curl), curl_errno($curl));
			} catch (Exception $e) {
				curl_close($curl);
				throw $e;
			}
		}

		$this->analyzeCodeAndMessage();

		$this->_data = json_decode($this->getRawData(), true);
		return $this->getData();
	}

	protected function doRequestWithLog($method, $args, $data)
	{
		list($url, $data) = $this->normalizeUrlWithSignature($method, $args, $data);

		$log = $this->createRestApiLog();

		$log->request_method = $method;

		$log->request_url = $url;
		$log->src_ip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR']:'localhost';
		$log->post_data = http_build_query($data, '', '&');

		$curl = $this->initCurl($method, $url, $data);

		curl_setopt($curl, CURLINFO_HEADER_OUT, true);

		$this->_header = '';
		$this->_rawData = curl_exec($curl);

		$log->duration = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
		$log->dst_ip = $this->getServerAddrFromUrl($url);

		if ($this->_rawData === false) {
			try {
				throw new Exception(curl_error($curl), curl_errno($curl));
			} catch (Exception $e) {
				curl_close($curl);

				$log->request_headers = 'Error';
				$log->response_code = 0;

				$log->response_headers = "ERROR\n";
				$log->response_headers .= "Code: ".$e->getCode()."\n";
				$log->response_headers .= "Message: ".$e->getMessage()."\n";
				$log->response_headers .= "Trace:\n".$e->getTraceAsString()."\n";

				$this->saveLog($log);

				throw $e;
			}
		}

		$this->analyzeCodeAndMessage();
		$log->request_headers = curl_getinfo($curl, CURLINFO_HEADER_OUT);
		$log->response_code = $this->getCode();
		$log->response_headers = $this->_header;
		$log->response_data = $this->_rawData;

		$this->_data = json_decode($this->getRawData(), true);
		curl_close($curl);

		$this->saveLog($log);

		return $this->getData();
	}

	protected function request($method, $args, $data)
	{
		$profileBlockId = 'ext.common.RESTful.RESTCaller: '.$method.' '.$this->getUrl($args);
		Yii::beginProfile($profileBlockId, 'RESTCaller');

		if (preg_match('{^(\w+)://([\w_]+)}', $this->logRequest, $m))
			$this->logRequest = Yii::app()->$m[1]->getRegistry($m[2]);

		if ($this->logRequest) return $this->doRequestWithLog($method, $args, $data);

		$result = $this->doRequestWithoutLog($method, $args, $data);

		Yii::endProfile($profileBlockId, 'RESTCaller');

		return $result;
	}

	function get($args = array())
	{
		return $this->request('GET', $args, array());
	}

	function post($data, $args = array())
	{
		return $this->request('POST', $args, $data);
	}

	function put($data, $args = array())
	{
		return $this->request('PUT', $args, $data);
	}
}

?>
