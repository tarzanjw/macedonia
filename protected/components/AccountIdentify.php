<?php

class AccountIdentify extends CUserIdentity
{
	const ERROR_NOT_ACTIVATED_YET 	= 101;
	const ERROR_LOCKED 				= 102;
	const ERROR_INCORRECT_USERNAME_OR_PASSWORD = 103;

	/**
	* @var Acc
	*/
	private $_acc;

	function authenticate()
	{
		/** @var Acc $acc */
		$acc = null;
		if (!is_null($phone = TextHelper::normalizePhoneNumber($this->username)))
			$acc = Acc::model()->findByAttributes(array('phone'=>$phone));
		elseif (!is_null($email = TextHelper::normalizeEmail($this->username)))
			$acc = Acc::model()->findByAttributes(array('email'=>$email));

		if (is_null($acc)) return self::ERROR_UNKNOWN_IDENTITY;

		$this->_acc = $acc;
		$this->setState('first_name', $acc->first_name);
		$this->setState('last_name', $acc->last_name);
		$this->setState('email', $acc->email);
		$this->setState('phone', $acc->phone);

		if ($acc->status == Acc::STATUS_LOCKED) return self::ERROR_LOCKED;
		if ($acc->status == Acc::STATUS_NOT_ACTIVATED_YET) return self::ERROR_NOT_ACTIVATED_YET;

		$auth = $acc->auth;
		if (empty($auth)) return self::ERROR_INCORRECT_USERNAME_OR_PASSWORD;

		if ($auth->password !== SecurityHelper::hashPassword($this->password, $auth->password_salt))
			return self::ERROR_INCORRECT_USERNAME_OR_PASSWORD;

		return self::ERROR_NONE;
	}

	function getId() { return !isset($this->_acc) ? null:$this->_acc->id; }
	function getName() { return !isset($this->_acc) ? null:($this->_acc->first_name.' '.$this->_acc->last_name); }
}