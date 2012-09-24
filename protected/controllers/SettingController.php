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

	public function actionInfo($action=null)
	{               
		$accModel = $this->getCurrentAccount();
		$infoModel = new ChangeInfoForm();
		if(!empty($action) && $action == 'edit'){
			$this->editInfo($accModel,$infoModel);
			return;
		}
		$this->render('info',array(
			'accModel' => $accModel,
			'infoModel' => $infoModel,
		));
	}

	public function EditInfo(Acc $accModel,ChangeInfoForm $infoModel)
	{
		if(isset($_POST['ChangeInfoForm'])){
			$infoModel->setAttributes($_POST['ChangeInfoForm'],false);
			if ($infoModel->validate()){
				$accModel->first_name = $infoModel->first_name;
				$accModel->last_name = $infoModel->last_name;
				$accModel->gender = $infoModel->gender;
				$accModel->dob = $infoModel->dob;
				$accModel->address = $infoModel->address;
				$accModel->city_id = $infoModel->city_id;
				if($accModel->save())
					$this->redirect('/setting/info');
			}                                                 
		}
		
		$this->render('info/_change_info',array(
			'accModel' => $accModel,
			'infoModel' => $infoModel,	
		));
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