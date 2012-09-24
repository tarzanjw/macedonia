<?php

/**
* Class này phục vụ các thao tác verify
*
* @author Tarzan <hanv9488@gmail.com>
*/
class VerifySMSComponent extends CApplicationComponent
{
	public static function verifySMS($acc_id,$sms_code)
	{   
		$item_id = 'account_id_'.$acc_id;
		$result = Yii::app()->otpCentral->check(18,$item_id,$sms_code);
		return $result;
	}
	
	public static function verifyPhone($item_id,$kind_id,$sms_code)
	{
		$result = Yii::app()->otpCentral->check($kind_id,$item_id,$sms_code,1);
		return $result;
	}
}

