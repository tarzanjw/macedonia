<?php

class SettingController extends Controller
{
	public $layout = '//setting/_layout';
	public $defaultAction = 'index';
	
	function actions()
	{
		return array(
			'security'=>array(
				'class'=>'application.controllers.setting.SecurityAction',
			),
		);
	}

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

	public function actionSecurity1()
	{
		$secretQuestions = SecretQuestion::model()->getAllQuestionAsArray();
		$acc_id = Yii::app()->user->id;
		$accModel = Acc::model()->with('auth')->findByPk($acc_id);
		$createQuestionFormModel = new CreateSecretQuestionForm();
		$changePhoneFormModel = new ChangePhoneForm();
		$verifyOtpChangePhoneForm = new VerifyOtpChangePhoneForm();
		$captchaModel = new CaptchaForm();
		$changePhoneFormModel->phone = $accModel->phone;
		$is_validate_pass = true;
		$is_validate_create_question = true;
		$is_validate_phone = true;
		$show_verify_otp = false;
				
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
			// TODO : -o nhập captcha
			$changePhoneFormModel->setAttributes($_POST['ChangePhoneForm'],false);
			echo '<pre>', print_r($_POST, true), '</pre>';die;
			if ($changePhoneFormModel->validate()) {
//				 $changePhoneResult = $this->changePhone($changePhoneFormModel,$accModel);
//				if($changePhoneResult == true){
//					Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã cập nhật số điện thoại thành công'));	
//					$this->redirect('/setting/security');	
//				}
				$phone_no = $changePhoneFormModel->phone;
				Yii::app()->otpCentral->send(KIND_CHANGE_PHONE,'change_phone_'.$acc_id.'_'.$phone_no,$phone_no);
				$show_verify_otp = true;
				$verifyOtpChangePhoneForm->phone = $phone_no;
			}else{
					$is_validate_phone = false;
			}
		}
		
		if(isset($_POST['VerifyOtpChangePhoneForm'])){
			$phone = $_POST['VerifyOtpChangePhoneForm']['phone'];
			$sms_code = $_POST['VerifyOtpChangePhoneForm']['otp'];
			$item_id = 'change_phone_'.$acc_id.'_'.$phone;
			$check = VerifySMSComponent::verifyPhone($item_id,KIND_CHANGE_PHONE,$sms_code);
			if($check == true){
				if($this->changePhone($accModel,$phone) == true){
					Yii::app()->user->setFlash('success', Yii::t('view','Thay đổi số điện thoại thành công'));	
					$this->redirect('/setting/security');	
				}
			}else{
				$verifyOtpChangePhoneForm->phone = $phone;
				$show_verify_otp = true;
				Yii::app()->user->setFlash('warning', Yii::t('view','Nhập sai mã kích hoạt'));	
			}
		}
		
		$this->render('security',array(
			'changePassFormModel' => $changePassFormModel,
			'createQuestionFormModel' => $createQuestionFormModel,
			'changePhoneFormModel' => $changePhoneFormModel,
			'verifyOtpChangePhoneForm' => $verifyOtpChangePhoneForm,
			'captchaModel' => $captchaModel,
			'accModel' => $accModel,
			'secretQuestions' => $secretQuestions,
			'is_validate_pass' => $is_validate_pass,
			'is_validate_create_question' => $is_validate_create_question,
			'is_validate_phone' => $is_validate_phone,
			'show_verify_otp' => $show_verify_otp,
			'tab'=>'general',
		));
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
				// TODO : -o Hocdt tự động đăng nhập khi verify email = url
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
		$item_id = 'account_id_'.$acc_id;			
		$check = VerifySMSComponent::verifyPhone($item_id,KIND_SIGNUP,$sms_code);
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
		Yii::app()->otpCentral->send(KIND_SIGNUP,'account_id_'.$acc_id,$phone,array(),false,true);
		return true;	
	}
	
	public function resendEmail($acc_id,$email)
	{
		Yii::app()->otpCentral->send(KIND_SIGNUP_EMAIL,'account_id_'.$acc_id,$email,array(),false,true);
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