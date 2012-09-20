<?php
  class ChangeSecretQuestion extends CFormModel
  {
  	public $_accModel;
  	public $password;
  	public $secret_question;
  	public $secret_answer;
  	
  	function init()
		{
			$this->getAccModel();
		}
		
	function attributeLabels()
	{
		return array(
			'password'=>Yii::t('view', 'Mật khẩu').':',
			'secret_question'=>Yii::t('view', 'Câu hỏi bảo mật').':',
			'secret_answer'=>Yii::t('view', 'Trả lời câu hỏi bảo mật').':',
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
	
	function rules()
	{
		return array(
			array('password', 'required',
					'message'=>Yii::t('view', 'Bạn phải nhập mật khẩu'),),
			array('secret_question', 'required',
					'message'=>Yii::t('view', 'Bạn phải chọn câu hỏi bảo mật'),),
			array('secret_answer', 'required',
					'message'=>Yii::t('view', 'Bạn phải nhập câu trả lời'),),	
									
			array('secretQuestion, oldPassword', 'requiredWithCondition','clientValidate'=>'clientRequiredWithCondition',
            	'invalidMessage'=>Yii::t('view', 'Bạn phải nhập mật khẩu hiện tại hoặc câu hỏi bảo mật'),),
			
			array('confirmed_password', 'required',
					'message'=>Yii::t('view', 'Bạn phải nhập xác nhận mật khẩu'),),
			array('newPassword','validatePassword','clientValidate'=>'clientValidatePassword',
            		'invalidMessage'=>Yii::t('view', 'Mật khẩu ít nhất 8 ký tự.'),),
            array('confirmed_password','validateConfirmedPassword','clientValidate'=>'clientValidateConfirmedPassword',
            		'invalidMessage'=>Yii::t('view', 'Bạn cần nhập lại đúng mật khẩu.')),
		);
	}  		  
  }
?>
