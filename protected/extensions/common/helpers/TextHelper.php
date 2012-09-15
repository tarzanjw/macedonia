<?php

class TextHelper
{
	/**
	* Chuẩn hóa số điện thoại về 1 dạng duy nhất
	*
	* @param string $text số điện thoại cần chuẩn hóa
	*
	* @return string|null NULL nếu không chuẩn hóa được
	*/
	static function normalizePhoneNumber($text)
	{
		$pattern = '{^(?:84|0)(9\d{8}|1\d{9})$}is';
		$text = preg_replace('{[\+\-\(\)\[\]\|\s:]}', '', $text);
		if (preg_match($pattern, $text, $m)) {
			return '+84'.$m[1];
		}

		return null;
	}

	/**
	* Chuẩn hóa email về 1 dạng duy nhất
	*
	* @param string $text email cần chuẩn hóa
	*
	* @return string|null NULL nếu không chuẩn hóa được
	*/
	static function normalizeEmail($text)
	{
		$text = trim($text);
		$v = new CEmailValidator();
		$v->allowEmpty = false;

		if (!$v->validateValue($text)) return null;

		return strtolower($text);
	}

	/*
	* Hàm này nhận đầu vào là chuỗi tiếng Việt có dấu
	* Trả về chuỗi tiếng Việt không dấu
	*
	* @param string $str Chuỗi tiếng việt có dấu
	* @param $delimiter Chuối sẽ thay thế các ký tự không phải chữ cái
	*
	* @return string
	*/
	public static function makeSlug($str, $delimiter='-')
	{
		$str=str_replace("\n", "", $str);
			$str = trim($str);

			$strFind = array(
					'đ','Đ',
					'á','à','ạ','ả','ã','Á','À','Ạ','Ả','Ã','ă','ắ','ằ','ặ','ẳ','ẵ','Ă','Ắ','Ằ','Ặ','Ẳ','Ẵ','â','ấ','ầ','ậ','ẩ','ẫ','Â','Ấ','Ầ','Ậ','Ẩ','Ẫ',
					'ó','ò','ọ','ỏ','õ','Ó','Ò','Ọ','Ỏ','Õ','ô','ố','ồ','ộ','ổ','ỗ','Ô','Ố','Ồ','Ộ','Ổ','Ỗ','ơ','ớ','ờ','ợ','ở','ỡ','Ơ','Ớ','Ờ','Ợ','Ở','Ỡ',
					'é','è','ẹ','ẻ','ẽ','É','È','Ẹ','Ẻ','Ẽ','ê','ế','ề','ệ','ể','ễ','Ê','Ế','Ề','Ệ','Ể','Ễ',
					'ú','ù','ụ','ủ','ũ','Ú','Ù','Ụ','Ủ','Ũ','ư','ứ','ừ','ự','ử','ữ','Ư','Ứ','Ừ','Ự','Ử','Ữ',
					'í','ì','ị','ỉ','ĩ','Í','Ì','Ị','Ỉ','Ĩ',
					'ý','ỳ','ỵ','ỷ','ỹ','Ý','Ỳ','Ỵ','Ỷ','Ỹ'
					);
			$strReplace = array(
					'd','d',
					'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
					'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
					'e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e','e',
					'u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u','u',
					'i','i','i','i','i','i','i','i','i','i',
					'y','y','y','y','y','y','y','y','y','y'
					);
			# \W = ^\w = ^a-zA-Z0-9_
			$str = str_replace($strFind, $strReplace, $str);
			$str = preg_replace( '/[^\w-\s]+/i','', $str);
			$str = preg_replace('{[\s_-]+}', $delimiter, $str);
			$str=strtolower($str);

			return $str;
	}
}