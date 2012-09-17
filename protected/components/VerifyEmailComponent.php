<?php

/**
* Class này phục vụ các thao tác verify
*
* @author Tarzan <hanv9488@gmail.com>
*/
class VerifyEmailComponent extends CApplicationComponent
{
	public static function verifyEmail($acc_id,$code)
	{   
    	$item_id = 'account_id_'.$acc_id;
		$result = Yii::app()->otpCentral->check(19,$item_id,$code);
		return $result;
	}
}

