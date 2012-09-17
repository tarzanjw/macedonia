<?php

/**
* Class này phục vụ các thao tác verify
*
* @author Tarzan <hanv9488@gmail.com>
*/
class VerifyEmailComponent extends CApplicationComponent
{
	public static function verifyEmail($email_id,$code)
	{   
		if(!empty($code))
			return true;
		return false;
	}
}

