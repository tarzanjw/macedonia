<?php

/**
* @property string $name
* @property string $dob
*/
class CaptchaForm extends CFormModel
{
	public $captcha;

	
	function attributeLabels()
	{
		return array(
			'captcha'=>Yii::t('view', 'Nhập Captcha'),
		);
	}

	function rules()
	{
		return array(
			array('captcha', 'ext.common.recaptcha.EReCaptchaValidator', 
               		'privateKey'=>'6LddktYSAAAAALfkgJj6Xvb7wBYmAUDVV9Fr-Wc-'),
		);
	}
}