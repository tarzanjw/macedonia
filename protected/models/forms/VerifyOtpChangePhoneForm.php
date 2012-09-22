<?php
   class VerifyOtpChangePhoneForm extends CFormModel
  {
  		public $phone;
  		public $otp;
  		
  	function attributeLabels()
	{
		return array(
			'phone'=>Yii::t('view', 'Số điện thoại mới').':',
			'otp'=>Yii::t('view', 'Mã xác minh số điện thoại').':',
		);
	}
	
	function rules()
	{
		return array(
			array('otp', 'required',
				'message'=>Yii::t('view', 'Bạn chưa nhập {attribute}.'),
			),
		);
	}
  }
?>
