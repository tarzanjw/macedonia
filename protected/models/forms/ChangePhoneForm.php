<?php
  class ChangePhoneForm extends CFormModel
  {
  	public $_accModel;
  	public $password;
  	public $phone;
  	public $captcha;
  	public $otp;
  	
  	function init()
	{
		$this->getAccModel();
	}
		
	function attributeLabels()
	{
		return array(
			'password'=>Yii::t('view', 'Mật khẩu').':',
			'phone'=>Yii::t('view', 'Số điện thoại').':',
			'otp'=>Yii::t('view', 'Mã xác minh số điện thoại').':',
		);
	}
	
	public function getAccModel()
	{
		
		if (!isset($this->_accModel)) {
			$acc_id = Yii::app()->user->id;
			$this->_accModel = Acc::model()->with('auth')->findByPk($acc_id); 
			if(empty($this->_accModel))
				throw new CHttpException(403,'Not authorize.');
		}

		return $this->_accModel;
	}
	
	function checkPassword($field,$params)
	{
		if($this->accModel->auth->password !== SecurityHelper::hashPassword($this->password, $this->accModel->auth->password_salt)){
			$this->addError('password',$params['invalidMessage']);
			return;		
		}
	}
	
//	function checkPhone($field,$params)
//	{

//		if($this->accModel->phone != $this->phone){
//			$acc = Acc::model()->findByAttributes(array('phone'=>$this->phone));
//			if(!empty($acc)){
//				$this->addError('phone',Yii::t('view','Số điện thoại đã sử dụng cho tài khoản khác'));
//				return;		
//			}
//		}else{
//			$this->addError('phone',yii::t('view','Số điện thoại trùng số điện thoại cũ'));
//		}
//		
//		
//	}
	
	function rules()
	{
		return array(
			array('password', 'required',
					'message'=>Yii::t('view', 'Bạn phải nhập mật khẩu'),),
			array('phone','filter','filter'=>array('TextHelper','normalizePhoneNumber'),),
			array('phone', 'required',
					'message'=>Yii::t('view', 'Bạn phải nhập số điện thoại'),),
			array('password', 'checkPassword','invalidMessage'=>Yii::t('view','Sai mật khẩu'),),
//			array('phone', 'checkPhone','invalidMessage'=>Yii::t('view','Số điện thoại không đúng'),),
			array('phone','unique','className'=>'Acc'),
			array('captcha', 'ext.common.recaptcha.EReCaptchaValidator',
               		'privateKey'=>'6Lc_YNYSAAAAADl9meEKpYWaA2RRrhaZG2sVUQwV',
               	),
		);
	} 
	 
  }
?>
