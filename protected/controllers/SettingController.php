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
		$this->render('security');
	}
	public function actionActivate()
	{
		$user_id = Yii::app()->user->id;
		$accModel = $this->loadAccModel($user_id);
		
		$captchaModel = new CaptchaForm();
		$captcha_error = false;
		$kind = 'sms';
		
		if (isset($_POST['CaptchaForm'])) {
			$kind = $_POST['kind'];
			$captchaModel->setAttributes($_POST['CaptchaForm'], false);
			if ($captchaModel->validate()){
				if($kind == 'sms'){
					if($this->resendSMS())
						Yii::app()->user->setFlash('success-sms', Yii::t('view','Gửi lại mã kích hoạt sms thành công'));	
					else Yii::app()->user->setFlash('error-sms', Yii::t('view','Gửi tin nhắn lỗi'));	
				}else{
					$kind = 'email';
					if($this->resendSMS())
						Yii::app()->user->setFlash('success-email', Yii::t('view','Gửi lại mã kích hoạt email thành công'));	
					else Yii::app()->user->setFlash('error-email', Yii::t('view','Gửi email lỗi'));	
				}
					
			}else {
				$captcha_error = true;
			}
		}
		
		if(isset($_POST['sms_code'])){
			$this->verifySMS($accModel,$_POST['sms_code']);	
		}
		
		if(isset($_POST['email_code'])){
			$this->verifyEmail($accModel,$_POST['email_code']);
		}
		
		$this->render('activate',array(
						'accModel'=>$accModel,
						'captchaModel'=>$captchaModel,
						'captcha_error'=>$captcha_error,
						'kind'=>$kind,
						));
	}
	
	public function verifySMS($accModel,$sms_code)
	{
		
		if($accModel->status == 'NORMAL' && $accModel->is_phone_verified == 1){
			Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực số điện thoại trước đó'));
			$this->redirect('/setting');	
		}
		
			/**
			* @var SmsVerify
			*/
			$smsVerifyModel = SmsVerify::model()->findByAttributes(array('acc_id'=>$accModel->id));
        	if(!empty($smsVerifyModel)){
				$check = VerifySMSComponent::verifySMS($smsVerifyModel->id,$sms_code);
				if($check){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực số điện thoại thành công'));
						$accModel->status = 'NORMAL';
						$accModel->is_phone_verified = 1;
						$accModel->save();
						$this->redirect('/setting/activate');
        			}
				else{
					 Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
				}        			
        		
        	} else  Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
        	
	}
	
	public function verifyEmail($accModel,$code)
	{
		/**
		* 
		* @var Acc
		*/
		if($accModel->status == 'NORMAL' && $accModel->is_email_verified == 1){
			Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email trước đó'));
			$this->redirect('/setting');	
		}
		// check xem đã verify chưa
				$check = VerifyEmailComponent::verifyEmail('test',$code);
				if($check){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email thành công'));
						$accModel->status = 'NORMAL';
						$accModel->is_email_verified = 1;
						$accModel->save();
						$this->redirect('/setting/activate');
        			}
				else
					 Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
	}
	
	public function resendSMS()
	{
		return true;	
	}
	
	public function resendEmail()
	{
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