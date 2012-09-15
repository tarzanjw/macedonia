<?php

/**
* Class này phục vụ các thao tác verify
*
* @author Tarzan <hanv9488@gmail.com>
*/
class VerifySMSComponent extends CApplicationComponent
{
	public static function verifySMS($sms_id,$sms_code)
	{   
		$smsModel = SmsVerify::model()->findByPk($sms_id);
		if(empty($smsModel)) return false;
		if($sms_code == $smsModel->acc_id)
			return true;				
		else return false;
	}
}

