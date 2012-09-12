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
}