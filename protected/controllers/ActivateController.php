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
        	}
        	if($check)
        	Yii::app()->user->setFlash('verifySMS','Bạn đã kích hoạt thành công');
		}
		$this->render('index');
	}
}
