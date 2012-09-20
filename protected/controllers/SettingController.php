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
		$secretQuestions = SecretQuestion::model()->findAll();
		$acc_id = Yii::app()->user->id;
		$accModel = Acc::model()->with('auth')->findByPk($acc_id);
		$is_validate_pass = true;
		
		
		$changePassFormModel = new ChangePassForm();
		if (isset($_POST['ChangePassForm'])) {
        		$changePassFormModel->setAttributes($_POST['ChangePassForm'], false);

			if ($changePassFormModel->validate()) {
				$changePass = $this->changePassword($changePassFormModel,$accModel,$_POST['ChangePassForm']);        		    
				
				if($changePass == true){
					Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã thay đổi mật khẩu thành công'));	
					$this->redirect('/setting/security');	
				}
			}else {
				$is_validate_pass = false;
			}
        	
		}
		
		$this->render('security',array(
			'changePassFormModel' => $changePassFormModel,
			'accModel' => $accModel,
			'secretQuestions' => $secretQuestions,
			'is_validate_pass' => $is_validate_pass,
		));
	}
	
	public function changePassword($changePassFormModel,$accModel,$post)
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