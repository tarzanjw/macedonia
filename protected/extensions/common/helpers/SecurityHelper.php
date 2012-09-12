<?php

class SecurityHelper
{
	static function generateSalt($length=16)
	{
		$dictionary = 'qwertyuiopaasdfghjklzxcvbnm1234567890MNBVCXZLKJHGFDSAPOIUYTREWQ';
		$l = strlen($dictionary)-1;

		$salt = '';
		while (strlen($salt) < $length) $salt .= $dictionary[rand(0, $l)];

		return $salt;
	}

	static function hashPassword($plainPassword, $salt)
	{
		return hash_hmac('sha1', $plainPassword, $salt);
	}
}