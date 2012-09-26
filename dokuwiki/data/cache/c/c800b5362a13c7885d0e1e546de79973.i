a:3:{i:0;a:3:{i:0;s:14:"document_start";i:1;a:0:{}i:2;i:0;}i:1;a:3:{i:0;s:4:"code";i:1;a:3:{i:0;s:5534:"
<?php

/**
* Class này cho phép các website tham gia vào quá trình SSO của VatgiaID
*
* Các có thể thay đổi các tham số sau đây :
* 	$gsnCookieName: tên của cookie sẽ lưu trữ _gsn, không khuyến khích thay đổi.
* 	$gsnSalt: giá trị ngẫu nhiên, gây nhiễu cho GSN, nên thay đổi.
* 	$validTimestamp: thời gian chênh lệch giữa 2 server (SSO và Website) + thời gian trên đường truyền. Không nên thay đổi.
*	$publicKey: thay đổi theo yêu cầu của id.vatgia.com
*
* @author Tarzan <hocdt85@gmail.com>
*/
class SSOHelper
{
    static public $gsnCookieName = '_gsn';
    static public $gsnSalt = 'rand-rts:*&^%$#@!';
	static public $validTimestamp = 180; # 3 minutes

	static public $validReferer = '{^https?://vid\.x/sso}i';

	static public $publicKey = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtyircuamZJJ0IM2flXVA
nTsiAmrjavGGDQrIR91S+LyEUCUNol/JzZu3vKEDBBnylCR6cBOn74O3r4NrHeii
0JVIC2j2HHRLiP6/VnCIlABFSpphKP6A7wTdPB2QxAvDWvItXiJu0ur0GrELIanI
pb5GMA9yJsusY5UR40a9oFMbguNnCRuih8FRPB3O71gMWqEN8yNdjgKgpFkUIqip
bspaVrp6IJJI6+fSWm5nm7/7x03RRYf7jUzHOg07ttp++9788bpwcdpNJW8Be1z7
r7FE8RjV08enuwue/7GPxV0RB7x3alDD3wkA3b1QAFD0yZMak4QG1eI4DvGODFwj
LwIDAQAB
-----END PUBLIC KEY-----
';

	static public $privateKey = '';

	const OPENSSL_PADDING = OPENSSL_PKCS1_PADDING;

	const ERROR_NONE=0;
	const ERROR_INVALID_REQUEST=1;

	/**
	* Decrypt a text use RSA Public key {@link SSO::PUBLIC_KEY}
	*
	* @param string $cipherText the cipher text
	*
	* @return string FALSE if the $cipherText is invalid
	*/
	static protected function decrypt($cipherText)
	{
        $pubKey = openssl_pkey_get_public(self::$publicKey);
        assert('$pubKey !== false');

        $cipherText = base64_decode($cipherText, true);
        if ($cipherText === false) return false;
        $x = openssl_public_decrypt($cipherText, $plainText, $pubKey, self::OPENSSL_PADDING);
        if (!$x) return false;

        return $plainText;
	}

	static protected function encrypt($plainText)
	{
        $privateKey = openssl_pkey_get_private(self::$privateKey);
        assert('$privateKey !== false');

		$x = openssl_private_encrypt($plainText, $cipherText, $privateKey, self::OPENSSL_PADDING);
		assert($x);

		return base64_encode($cipherText);
	}

	/**
	* Kiểm tra referer của request hiện tại
	*
	* @return boolean
	*/
	static protected function isRefererValid()
	{
        if (!isset($_SERVER['HTTP_REFERER'])) return false;

        return preg_match(self::$validReferer, $_SERVER['HTTP_REFERER']);
	}

	/**
	* Giải mã request cho SetSID. Trả lại thông tin nếu request hợp lệ
	*
	* @property string $email output: biến giữ giá trị của email nhận được
	* @property array $data output: mảng các thông tin của người dùng
	* @property string $gsn output: biến giữ giá trị của GSN nhận được
	*
	* @return integer 1 trong các giá trị của SSOHelper::ERROR_XXX
	*/
	static public function decodeSetSIDRequest(&$email, &$data, &$gsn)
	{
		if (!self::isRefererValid()) return self::ERROR_INVALID_REQUEST;

        if (!isset($_GET['token'])) return self::ERROR_INVALID_REQUEST;
    	$token = $_GET['token'];

        $token = self::decrypt($token);
        if ($token === false) return self::ERROR_INVALID_REQUEST;

        $token = json_decode($token, true);
		if (is_null($token)) return self::ERROR_INVALID_REQUEST;

        $email = $token['email'];
        $gsn = $token['gsn'];
        $data = $token['data'];
        $timestamp = $token['timestamp'];
        if (time() > $timestamp + self::$validTimestamp) return self::ERROR_INVALID_REQUEST;

        if (empty($email) || empty($gsn)) return self::ERROR_INVALID_REQUEST;

        return self::ERROR_NONE;
	}

	/**
	* Giải mã request ClearSID
	*
	* @return integer self::ERROR_XXX
	*/
	static public function decodeClearSIDRequest()
	{
        if (!isset($_GET['gsn'])) return self::ERROR_INVALID_REQUEST;

        if (!self::checkGSN($_GET['gsn'])) return self::ERROR_INVALID_REQUEST;

        return self::ERROR_NONE;
	}

	/**
	* Lưu lại GSN vào cookie để so sánh sau này.
	*
	* @param string $gsn gsn sẽ được lưu
	*/
    static function saveGSN($gsn)
    {
    	$gsn = hash_hmac('md5', $gsn, self::$gsnSalt);
    	setcookie(self::$gsnCookieName, $gsn, time()+86400); // 1 day
    }

    static function clearGSN()
    {
		setcookie(self::$gsnCookieName, null, 946659600); # 946659600 = 2000/01/01 00:00:00
    }

    /**
    * Kiểm tra xem GSN có trùng với GSN đã đc lưu trên cookie hay không?
    *
    * @param string $gsn GSN để kiểm tra
    *
    * @return boolean
    */
	static function checkGSN($gsn)
	{
    	if (!isset($_COOKIE[self::$gsnCookieName]) || empty($_COOKIE[self::$gsnCookieName])) return true;

    	$cGsn = $_COOKIE[self::$gsnCookieName];
    	$gsn = hash_hmac('md5', $gsn, self::$gsnSalt);

    	return $gsn == $cGsn;
	}

	/**
	* Encrypt data for SetSID request
	*
	* @param string $email
	* @param string $gsn
	*/
	static public function encryptSetSIDRequest($email, $gsn, $data=array())
	{
    	$token = array(
        	'email'=>$email,
        	'gsn'=>$gsn,
        	'data'=>$data,
        	'timestamp'=>time(),
    	);

    	return self::encrypt(json_encode($token));
	}

	static public function returnImage()
	{
		header('Content-Type: image/gif');

		# this is an image with 1pixel x 1pixel
		$img = base64_decode('R0lGODdhAQABAPAAAL6+vgAAACwAAAAAAQABAAACAkQBADs=');
		echo $img;
	}
}
";i:1;s:3:"php";i:2;N;}i:2;i:6;}i:2;a:3:{i:0;s:12:"document_end";i:1;a:0:{}i:2;i:6;}}