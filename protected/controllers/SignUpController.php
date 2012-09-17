<?php

class SignUpController extends Controller
{
    public $layout='//signUp/_layout';

    /**
    * @param SignUpForm $form
    * @return Acc
    */
    protected function doCreateAccount(SignUpForm $form)
    {
		$acc = $form->createAccObject();

		if (!$acc->validate()) {
			foreach ($acc->getErrors() as $name=>$errors)
				foreach ($errors as $error) $form->addError($name, $error);
			return false;
		}

		$transaction = $acc->getDbConnection()->beginTransaction();

		try {
			$x = $acc->save();
			assert($x);

			$auth = new AccAuth();

			$auth->id = $acc->id;
			$auth->importPassword($form->password);
			$x = $auth->save();
			assert($x);

			$acc->addRelatedRecord('auth', $auth, false);

			$transaction->commit();
		}
		catch (Exception $e) {
			$transaction->rollback();
			throw $e;
		}

//		Yii::app()->email->sendVerifyEmail($acc);
//		Yii::app()->sms->sendVerifySMS($acc);
		Yii::app()->otpCentral->send(18,'account_id_'.$acc->id,$acc->phone);
		Yii::app()->otpCentral->send(19,'account_id_'.$acc->id,$acc->email);

		return $acc;
    }

	public function actionIndex()
	{
		
//		$id = 20;
//		Yii::app()->otpCentral->send(19,'acc_id'.$id,'hanv9488@gmail.com');
//		$test = Yii::app()->otpCentral->check(18,20,'YTYUZ');
//		echo '<pre>', print_r($test, true), '</pre>';
		$model = new SignUpForm();
      	$this->performAjaxValidation($model);
		if (isset($_POST['SignUpForm'])) {
			$model->setAttributes($_POST['SignUpForm'], false);

			if ($model->validate()) {
				if ($acc = $this->doCreateAccount($model)) $this->redirect(array('/SignIn'));
			}
		}

		$this->render('index', array(
			'model'=>$model,
		));
	}
	
	protected function performAjaxValidation($model)
	{
	    if(isset($_POST['ajax']) && $_POST['ajax']==='signUp-form')
	    {
	       	unset($_POST['SignUpForm']['name']);
	        echo CActiveForm::validate($model);
	     	
	        Yii::app()->end();
	    }
	}
}
