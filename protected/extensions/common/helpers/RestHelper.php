<?php

/**
* Class này phục vụ các tiện ích đối với RESTful
*
* @author Tarzan <hocdt85@gmail.com>
*/
class RestHelper
{
    const OPENSSL_SIGNATURE_ALGORITHM = OPENSSL_ALGO_SHA1;

    /**
    * Biến 1 mảng dữ liệu (nhiều cấp) thành 1 mảng các query strings
    * Query string trả về đã được encode theo url encode, chuẩn PHP_QUERY_RFC3986
    * (biến [space] thành %20 thay vì dấu +)
    *
    * @param array $data mảng dữ liệu 1+ cấp
    * @param array $queryStrings các phần tử của query string sẽ được ghép vào đây
    *
    * @return array query strings
    */
    static protected function _makeQueryStrings($data, & $queryStrings, $prefix=null)
    {
		foreach ($data as $k=>$v) {
			if (!empty($prefix)) $k = $prefix.'['.$k.']';
			if (is_array($v)) self::_makeQueryStrings($v, $queryStrings, $k);
			else $queryStrings[rawurlencode($k)] = rawurlencode($v);
		}
    }

    /**
    * Chuẩn hóa 1 mảng dữ liệu thành xâu để tính trong quá trình hashing
    *
    * @param array $data dữ liệu cần chuẩn hóa
    * @return string
    */
    static function normalizeArrayToString($data)
    {
		self::_makeQueryStrings($data, $items);
		ksort($items);

		$tmp = array();
		foreach ($items as $k=>$v) $tmp[] = $k.'='.$v;

		return implode('&', $tmp);
    }

	/**
	* Tạo ra dữ liệu để ký/kiểm tra chữ ký
	*
	* @param mixed $method
	* @param mixed $requestPath
	* @param mixed $queries
	* @param mixed $data
	*/
    static function makeSignatureData($method, $requestPath, $queries, $data)
    {
    	$queries = self::normalizeArrayToString($queries);
    	$data = self::normalizeArrayToString($data);
        $method=strtoupper($method);

        return
        	$method
        	.'&'.rawurlencode($requestPath)
        	.'&'.rawurlencode($queries).
        	'&'.rawurlencode($data);
    }

	/**
	* Kiểm tra chữ ký của một request lên RESTful API
	*
	* @param string $publicKey khóa công khai, theo chuẩn support bởi {@link http://www.php.net/manual/en/function.openssl-pkey-get-public.php}
	* @param string $signature chữ ký cần kiểm tra
	* @param string $method method dùng để request (GET, POST, PUT ...). Nếu giá trị = null sẽ lấy giá trị của request hiện thời
	* @param string $requestPath
	* @param string $queries
	* @param string $data
	*
	* @return boolean hợp lệ hay không
	*/
    static function verifySignature($publicKey, $signature, $method=null, $requestPath=null, $queries=null, $data=null)
    {
        if (is_null($method)) $method = $_SERVER['REQUEST_METHOD'];
        if (is_null($requestPath)) $requestPath = explode('?', $_SERVER['REQUEST_URI'], 2);
        if (is_null($queries)) $queries = $_GET;
        if (is_null($data)) $data = $_POST;

        $signature = base64_decode($signature);
        $publicKey = openssl_pkey_get_public($publicKey);
        assert('$publicKey !== false');

        $data = self::makeSignatureData($method, $requestPath, $queries, $data);
        return openssl_verify($data, $signature, $publicKey, self::OPENSSL_SIGNATURE_ALGORITHM);
    }

    /***
    * Tạo chữ ký cho 1 request
    *
    * @param mixed $privateKey
    * @param mixed $method
    * @param mixed $requestPath
    * @param mixed $get
    * @param mixed $post
    *
    * return string signature
    */
    static function makeSignature($privateKey, $method, $requestPath, $queries, $data)
    {
        $data = self::makeSignatureData($method, $requestPath, $queries, $data);
        $privateKey = openssl_pkey_get_private($privateKey);
        assert('$privateKey !== false');

        $x = openssl_sign($data, $sign, $privateKey, self::OPENSSL_SIGNATURE_ALGORITHM);
        assert('$x');

        return base64_encode($sign);
    }
}
