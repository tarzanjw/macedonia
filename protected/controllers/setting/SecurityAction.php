<?php

class SecurityAction extends CAction
{	
	public $currentTab;
	
	function tabGeneral()
	{
		$this->controller->render('security/_general', array(
			'acc'=>$this->controller->getCurrentUser(),
		));
	}
	
	function tabPassword()
	{
		$changePassFormModel = new ChangePassForm();
		$accModel = $this->controller->getCurrentUser(); 
		if (isset($_POST['ChangePassForm'])) {
        		$changePassFormModel->setAttributes($_POST['ChangePassForm'], false);

			if ($changePassFormModel->validate()) {
				$changePass = $this->changePassword($changePassFormModel,$accModel);        		    
				
				if($changePass == true){
					Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã thay đổi mật khẩu thành công'));	
					$this->controller->redirect('/setting/security');	
				}
			}
		}
		
		$this->controller->render('security/_password', array(
			'acc'=>$accModel,
			'changePassFormModel'=>$changePassFormModel,
		));
	}
	
	function tabPhone()
	{
		$accModel = $this->controller->getCurrentUser(); 
		$formModel = new ChangePhoneForm();
		$formModel->phone = $accModel->phone;
		$verifyOtpForm = new VerifyOtpChangePhoneForm();
		$show_verify_otp = false;
		if(isset($_POST['ChangePhoneForm'])){
			$formModel->setAttributes($_POST['ChangePhoneForm'],false);
			if ($formModel->validate()) {
				$phone_no = $formModel->phone;
				Yii::app()->otpCentral->send(KIND_CHANGE_PHONE,'change_phone_'.$accModel->id.'_'.$phone_no,$phone_no);
				$verifyOtpForm->phone = $phone_no;
				$show_verify_otp = true;
			}else{
					$is_validate_phone = false;
			}
		}
		
		if(isset($_POST['VerifyOtpChangePhoneForm'])){
			$phone = $_POST['VerifyOtpChangePhoneForm']['phone'];
			$sms_code = $_POST['VerifyOtpChangePhoneForm']['otp'];
			$item_id = 'change_phone_'.$accModel->id.'_'.$phone;
			$check = VerifySMSComponent::verifyPhone($item_id,KIND_CHANGE_PHONE,$sms_code);
			if($check == true){
				if($this->changePhone($accModel,$phone) == true){
					Yii::app()->user->setFlash('success', Yii::t('view','Thay đổi số điện thoại thành công'));	
					$this->Controller->redirect('/setting/security');	
				}
			}else{
				$verifyOtpForm->phone = $phone;
				$show_verify_otp = true;
				Yii::app()->user->setFlash('warning', Yii::t('view','Nhập sai mã kích hoạt'));	
			}
		}
		
		if($show_verify_otp){
			$this->controller->render('security/_phone_verify_otp', array(
				'acc'=>$accModel,
				'formModel'=>$verifyOtpForm,
				'show_verify_otp'=>$show_verify_otp,
			));	
			return;
		}
		$this->controller->render('security/_phone', array(
			'acc'=>$accModel,
			'formModel'=>$formModel,
			'show_verify_otp'=>$show_verify_otp,
		));
	}	
	
	function tabQuestion()
	{
		$accModel = $this->controller->getCurrentUser(); 
		$formModel = new SecretQuestionForm();

		if(isset($_POST['SecretQuestionForm'])){
			$formModel->setAttributes($_POST['SecretQuestionForm'], false);
			if ($formModel->validate()) {
					$result = $this->updateQuestion($formModel,$accModel);
		            if($result == true){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã cập nhật câu hỏi bảo mật thành công'));	
						$this->Controller->redirect('/setting/security');	
					}
				}
		}
		
		$this->controller->render('security/_update_question_form', array(
			'acc'=>$accModel,
			'formModel'=>$formModel,
		));	
		return;
	}
	
	function run($tab = 'general')
	{
		$this->controller->layout = 'application.views.setting.security._layout';
		$this->currentTab = $tab;
		
		switch ($tab) {
			case 'password':
				$this->tabPassword();
				break;
			case 'phone':
				$this->tabPhone();
				break;
			case 'question':
				$this->tabQuestion();
				break;
			default:
				$this->tabGeneral();
		}
	}	
		
	public function changePhone($accModel,$new_phone)
	{
		$accModel->phone = $new_phone;
		if($accModel->save()) return true;
		return false;
	}
	
	public function changePassword($changePassFormModel,$accModel)
	{
		/**
		* @var AccAuth
		*/
		$accAuth = $accModel->auth;
		if($changePassFormModel->verifyMethod == 'secretQuestion'){
			if($accAuth->secret_answer != SecurityHelper::hashPassword($changePassFormModel->secretQuestion, $accAuth->secret_answer_salt)){
				return false;	
			}
		}else{
			if($accAuth->password != SecurityHelper::hashPassword($changePassFormModel->oldPassword, $accAuth->password_salt)){
				return false;	
			}
		}
		$accAuth->password = SecurityHelper::hashPassword($changePassFormModel->newPassword,$accAuth->password_salt);
		if($accAuth->save()) return true;
		
		return false;
	}
	
	public function updateQuestion($createQuestionFormModel,$accModel)
	{
		/**
		* @var AccAuth
		*/

		$accAuth = $accModel->auth;
		if($accAuth->password != SecurityHelper::hashPassword($createQuestionFormModel->password, $accAuth->password_salt)){
				return false;	
		}
		if($createQuestionFormModel->secret_question == '0'){
			$accAuth->secret_question = null;
			$accAuth->secret_answer = null;		
		}else if($createQuestionFormModel->secret_question == '1'){
			$accAuth->secret_question = $createQuestionFormModel->another_question;
			$accAuth->importAnswer($createQuestionFormModel->secret_answer);	
		}else{
			$accAuth->secret_question = $createQuestionFormModel->secret_question;
			$accAuth->importAnswer($createQuestionFormModel->secret_answer);	
		}
		if($accAuth->save()) return true;
		return false;
		
	}
	
}