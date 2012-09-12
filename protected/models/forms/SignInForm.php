<?php

class SignInForm extends CFormModel
{
	public $username;
	public $password;
	public $remember;

	function attributeLabels()
	{
		return array(
			'username'=>Yii::t('view', 'Email / Số điện thoại'),
			'password'=>Yii::t('view', 'Mật khẩu'),
			'remember'=>Yii::t('view', 'Nhớ thái đăng nhập'),
		);
	}

	function rules()
	{
		return array(
			array('username, password', 'required'),
			array('username', 'filter', 'filter'=>'trim'),
		);
	}
}