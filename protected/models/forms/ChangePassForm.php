<?php
	class ChangePassForm extends CFormModel
	{
		const VERIFY_METHOD__PASSWORD='oldPassword';
		const VERIFY_METHOD__QUESTION='secretQuestion';
		
		public $verifyMethod='pwd';
		public $oldPassword;
		public $secretQuestion;
		public $newPassword;
		public $confirmed_password;
		public $_accModel;
		
		function init()
		{
			$this->getAccModel();
		}
		
		function attributeLabels()
		{
			return array(
				'oldPassword'=>Yii::t('view', 'Mật khẩu hiện tại').':',
				'newPassword'=>Yii::t('view', 'Mật khẩu mới').':',
				'confirmed_password'=>Yii::t('view', 'Xác nhận Mật khẩu').':',
				'secretQuestion'=>$this->accModel->auth->secret_question."?:",
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
		
		function validatePassword($field,$params)
		{
			if(strlen($this->newPassword) < 8){
				$this->addError('newPassword',$params['invalidMessage']);
				return;
			}
			if($this->accModel->auth->password == SecurityHelper::hashPassword($this->newPassword, $this->accModel->auth->password_salt)){
				$this->addError('newPassword',Yii::t('view','Mật khẩu mới trùng mật khẩu cũ'));
				return;		
			}
		}
		
		
		function clientValidatePassword()
		{
			$password = get_class($this).'_newPassword';
			$invalidMessage=Yii::t('view', 'Mật khẩu ít nhất 8 ký tự.');
			$js = <<<JS
var password = $('#{$password}').val();

if (password.length < 8) messages.push('{$invalidMessage}');
JS;

			return $js;		
		}
		
		function validateConfirmedPassword($field, $params)
		{
			if ($this->newPassword != $this->confirmed_password) {
				$this->addError('confirmed_password', $params['invalidMessage']);
				return;
			}

		}
		
	function clientValidateConfirmedPassword()
	{
		$password = get_class($this).'_newPassword';
		$rePassword = get_class($this).'_confirmed_password';
		$invalidMessage=Yii::t('view', 'Bạn cần nhập lại đúng mật khẩu.');

		$js = <<<JS
var password = $('#{$password}').val();
var rePassword = $('#{$rePassword}').val();

if (password != rePassword) messages.push('{$invalidMessage}');
JS;

		return $js;
	}
	
		function requiredWithCondition($field, $params)
		{
			if($this->verifyMethod == 'secretQuestion'){
				if (empty($this->secretQuestion)){
					$this->addError('secretQuestion',Yii::t('view', 'Bạn phải nhập câu hỏi bảo mật'));
					return;
				}
				if($this->accModel->auth->secret_answer != SecurityHelper::hashPassword($this->secretQuestion, $this->accModel->auth->secret_answer_salt)){
					$this->addError('secretQuestion',Yii::t('view', 'Trả lời sai câu hỏi bảo mật'));
					return;	
				}		
			}else{
				if (empty($this->oldPassword)){
					$this->addError('oldPassword',Yii::t('view', 'Bạn phải nhập mật khẩu cũ'));
					return;
				}
				if($this->accModel->auth->password !== SecurityHelper::hashPassword($this->oldPassword, $this->accModel->auth->password_salt))
				{
					$this->addError('oldPassword',Yii::t('view',Yii::t('view', 'Mật khẩu cũ không chính xác')));
					return;
				}		
			}
		}
	
		function clientRequiredWithCondition($field)
		{
			/*
			if($field == 'oldPassword'){
				$input_id= 'ChangePassForm_verifyMethod_oldPassword';
				$text_id='ChangePassForm_oldPassword';
				$invalidMessage = Yii::t('view', 'Bạn phải nhập mật khẩu cũ');	
			}else {
				$input_id= 'ChangePassForm_verifyMethod_secretQuestion';
				$text_id='ChangePassForm_secretQuestion';
				$invalidMessage = Yii::t('view', 'Bạn phải nhập câu hỏi bảo mật');	
			}

			
			
			return <<<JS
var value = $('#{$input_id}');
if(value.attr('checked')!='checked') return;
var text = $('#{$text_id}').val();
if(text.length < 1)
messages.push('{$invalidMessage}');
JS;
		*/
		}
	

		function rules()
		{
			return array(
				array('secretQuestion, oldPassword', 'requiredWithCondition','clientValidate'=>'clientRequiredWithCondition',
            		'invalidMessage'=>Yii::t('view', 'Bạn phải nhập mật khẩu hiện tại hoặc câu hỏi bảo mật'),),
				array('newPassword', 'required',
						'message'=>Yii::t('view', 'Bạn phải nhập mật khẩu mới'),),
				array('oldPassword', 'required',
						'message'=>Yii::t('view', 'Bạn phải nhập mật khẩu cũ'),),
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
