<?php

class SettingController extends Controller
{
	public $layout = '//setting/_layout';
	public $defaultAction = 'index';

	public function actionBaokim()
	{
		$this->render('baokim');
	}

	public function actionProducts()
	{
		$this->render('products');
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionInfo()
	{
		$this->render('info');
	}

	public function actionSecurity()
	{
		$secretQuestions = SecretQuestion::model()->getAllQuestionAsArray();
		$acc_id = Yii::app()->user->id;
		$accModel = Acc::model()->with('auth')->findByPk($acc_id);
		$changePassFormModel = new ChangePassForm();
		$createQuestionFormModel = new CreateSecretQuestionForm();
		$changePhoneFormModel = new ChangePhoneForm();
		$changePhoneFormModel->phone = $accModel->phone;
		$is_validate_pass = true;
		$is_validate_create_question = true;
		$is_validate_phone = true;
		
		
		if (isset($_POST['ChangePassForm'])) {
        		$changePassFormModel->setAttributes($_POST['ChangePassForm'], false);

			if ($changePassFormModel->validate()) {
				$changePass = $this->changePassword($changePassFormModel,$accModel);        		    
				
				if($changePass == true){
					Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã thay đổi mật khẩu thành công'));	
					$this->redirect('/setting/security');	
				}
			}else {
				$is_validate_pass = false;
			}
        	
		}
		
		if(isset($_POST['CreateSecretQuestionForm'])){
			$createQuestionFormModel->setAttributes($_POST['CreateSecretQuestionForm'], false);
			if ($createQuestionFormModel->validate()) {
					$createQuesstionResult = $this->updateQuestion($createQuestionFormModel,$accModel);
            		if($createQuesstionResult == true){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã cập nhật câu hỏi bảo mật thành công'));	
						$this->redirect('/setting/security');	
					}
				}else {
					$is_validate_create_question = false;
				}
		}
		
		if(isset($_POST['ChangePhoneForm'])){
			$changePhoneFormModel->setAttributes($_POST['ChangePhoneForm'],false);
			if ($changePhoneFormModel->validate()) {
				 $changePhoneResult = $this->changePhone($changePhoneFormModel,$accModel);
				if($changePhoneResult == true){
					Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã cập nhật số điện thoại thành công'));	
					$this->redirect('/setting/security');	
				}
			}else{
					$is_validate_phone = false;
			}
		}
		
		$this->render('security',array(
			'changePassFormModel' => $changePassFormModel,
			'createQuestionFormModel' => $createQuestionFormModel,
			'changePhoneFormModel' => $changePhoneFormModel,
			'accModel' => $accModel,
			'secretQuestions' => $secretQuestions,
			'is_validate_pass' => $is_validate_pass,
			'is_validate_create_question' => $is_validate_create_question,
			'is_validate_phone' => $is_validate_phone,
		));
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
		if($createQuestionFormModel->secret_answer == '******')
			return true;
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
	
	public function changePhone($changePhoneFormModel,$accModel)
	{
		$accAuth = $accModel->auth;
		if($accAuth->password != SecurityHelper::hashPassword($changePhoneFormModel->password, $accAuth->password_salt)){
				return false;	
		}
		$accModel->phone = $changePhoneFormModel->phone;
		if($accModel->save()) return true;
		return false;
	}
	
	public function actionActivate()
	{
		$acc_id = Yii::app()->user->id;
		$accModel = $this->loadAccModel($acc_id);
		
		$captchaModel = new CaptchaForm();
		$captcha_error = false;
		$kind = 'sms';
		
		if (isset($_POST['CaptchaForm'])) {
			$kind = $_POST['kind'];
			$captchaModel->setAttributes($_POST['CaptchaForm'], false);
			if ($captchaModel->validate()){
				if($kind == 'sms'){
					if($this->resendSMS($accModel->id,$accModel->phone))
						Yii::app()->user->setFlash('success-sms', Yii::t('view','Gửi lại mã kích hoạt sms thành công'));	
					else Yii::app()->user->setFlash('error-sms', Yii::t('view','Gửi tin nhắn lỗi'));	
				}else{
					$kind = 'email';
					if($this->resendEmail($accModel->id,$accModel->email))
						Yii::app()->user->setFlash('success-email', Yii::t('view','Gửi lại mã kích hoạt email thành công'));	
					else Yii::app()->user->setFlash('error-email', Yii::t('view','Gửi email lỗi'));	
				}
					
			}else{
				$captcha_error = true;
			}
		}
		
		if(isset($_POST['sms_code'])){
			$check_phone = $this->verifySMS($acc_id,$_POST['sms_code']);	
			if($check_phone){
				Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực số điện thoại thành công'));
				$this->redirect('/setting/activate');
			}else Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
		}
		
		if(isset($_POST['email_code'])){
			$check_email = $this->verifyEmail($acc_id,$_POST['email_code']);
			if($check_email){
				Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email thành công'));	
				$this->redirect('/setting/activate');
			}else Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt')); 
		}
		
		$this->render('activate',array(
						'accModel'=>$accModel,
						'captchaModel'=>$captchaModel,
						'captcha_error'=>$captcha_error,
						'kind'=>$kind,
						));
	}
	
	public function actionVerifyEmailByUrl($acc_id=null,$code=null)
	{
		// case: chưa đăng nhập => tự động đăng nhập
		// đã đăng nhập với tài khoản khác -> logout -> đăng nhập
		$currenAccId = Yii::app()->user->id; 
		if(!empty($currenAccId) && ($acc_id == $currenAccId)){
			$check_email = $this->verifyEmail($acc_id,$code);
			if($check_email){
				Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email thành công'));	
				$this->redirect('/setting/activate');	
			}else {
				Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt')); 	
				$this->redirect('/setting/activate');
			}
		}
		else{
			if(!empty($currenAccId)){
				Yii::app()->user->logout();	
			}
			$check_email = $this->verifyEmail($acc_id,$code);
			if($check_email) {
				die('tu dong dang nhap cho account');
				//tự động đăng nhập
				Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email thành công'));	
				$this->redirect('/setting/activate');	
			}else{
				Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt')); 	
				$this->redirect('/setting/activate');
			}
		}
	}
	
	
	
	public function verifySMS($acc_id,$sms_code)
	{
		$accModel = $this->loadAccModel($acc_id);
		if($accModel->status == 'NORMAL' && $accModel->is_phone_verified == 1){
			Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực số điện thoại trước đó'));
			$this->redirect('/setting');	
		}
			/**
			* @var SmsVerify
			*/
		$check = VerifySMSComponent::verifySMS($accModel->id,$sms_code);
		if($check){
				
				$accModel->is_phone_verified = 1;
				if($accModel->is_email_verified == 1)
					$accModel->status = 'NORMAL';
				$accModel->save();
				return true;
				$this->redirect('/setting/activate');
        	}
		else{
			return false;
		}        			
	}
	
	public function verifyEmail($acc_id,$code)
	{
		/**
		* 
		* @var Acc
		*/
		$accModel = $this->loadAccModel($acc_id);
		if($accModel->status == 'NORMAL' && $accModel->is_email_verified == 1){
			Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email trước đó'));
			$this->redirect('/setting');	
		}
		// check xem đã verify chưa
		$check = VerifyEmailComponent::verifyEmail($accModel->id,$code);
		if($check){
				$accModel->is_email_verified = 1;
				if($accModel->is_phone_verified == 1)
					$accModel->status = 'NORMAL';
				$accModel->save();
				return true;
        	}
		else
			return false;
	}
	
	public function resendSMS($acc_id,$phone)
	{
		Yii::app()->otpCentral->send(18,'account_id_'.$acc_id,$phone,array(),false,true);
		return true;	
	}
	
	public function resendEmail($acc_id,$email)
	{
		Yii::app()->otpCentral->send(19,'account_id_'.$acc_id,$email,array(),false,true);
		return true;	
	}
	
	public function loadAccModel($id)
	{
		$model=Acc::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}