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

		Yii::app()->email->sendVerifyEmail($acc);
		Yii::app()->sms->sendVerifySMS($acc);

		return $acc;
    }

	public function actionIndex()
	{
		$model = new SignUpForm();

		if (isset($_POST['SignUpForm'])) {
			$model->setAttributes($_POST['SignUpForm'], false);

			if ($model->validate()) {
				if ($acc = $this->doCreateAccount($model)) $this->redirect(array('/activate', 'id'=>$acc->id));
			}
		}

		$this->render('index', array(
			'model'=>$model,
		));
	}
}