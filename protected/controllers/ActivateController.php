<?php

class ActivateController extends Controller
{
    public $layout='//signUp/_layout';

  
	public function actionIndex($id)
	{  
		if (isset($_POST['sms_code'])) {
			$sms_code = $_POST['sms_code'];
			/**
			* 
			* @var SmsVerify
			*/
			$smsVerifyModel = SmsVerify::model()->findByAttributes(array('acc_id'=>$id));
        	if(!empty($smsVerifyModel)){
				$check = VerifySMSComponent::verifySMS($smsVerifyModel->id,$sms_code);
				if($check){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã kích hoạt thành công'));
						$this->redirect('setting/');
        			}
				else{
					 Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
				}        			
        		
        	} else  Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
        	
		}
		$this->render('index');
	}
	
	public function actionverifyPhone($id)
	{
		$accModel = $this->loadAccModel($id);
		// check xem đã verify chưa
		if (isset($_POST['sms_code'])) {
			$sms_code = $_POST['sms_code'];
			/**
			* @var SmsVerify
			*/
			$smsVerifyModel = SmsVerify::model()->findByAttributes(array('acc_id'=>$id));
        	if(!empty($smsVerifyModel)){
				$check = VerifySMSComponent::verifySMS($smsVerifyModel->id,$sms_code);
				if($check){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực số điện thoại thành công'));
						$accModel->status = 'NORMAL';
						$accModel->save();
						$this->redirect('/setting');
        			}
				else{
					 Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
				}        			
        		
        	} else  Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
        	
		}
		$this->render('verify_phone');	
	}
	
	public function actionVerifyEmail($id,$code = null)
	{
		$accModel = $this->loadAccModel($id);
		// check xem đã verify chưa
		if(isset($_POST['code']))	$code = $_POST['code'];
				$check = VerifyEmailComponent::verifyEmail('test',$code);
				if($check){
						Yii::app()->user->setFlash('success', Yii::t('view','Bạn đã xác thực email thành công'));
						$accModel->status = 'NORMAL';
						$accModel->save();
						$this->redirect('/setting');
        			}
				else
					 Yii::app()->user->setFlash('warning', Yii::t('view','Sai mã kích hoạt'));
        		
        	
		$this->render('verify_email');	
		
	}
	
	public function loadAccModel($id)
	{
		$model=Acc::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}
