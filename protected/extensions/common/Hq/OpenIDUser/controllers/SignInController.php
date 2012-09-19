<?php

class SignInController extends UserBaseController
{
	function processAfterLogIn()
	{
		assert('!Yii::app()->user->isGuest');

		$this->returnLastUrl();
	}

	function accessRules()
	{
		return array(
        	array('allow', 'actions'=>array('*')),
		);
	}

	/**
	* @inheritdoc
	*/
	protected function beforeAction($action)
	{
		if (!Yii::app()->user->isGuest) {
			$this->processAfterLogIn();
			return false;
		}

		return true;
	}

	function actions()
	{
		Yii::import($this->module->getId() . '.controllers.login.*');

		return array(
			'google'	=>	$this->module->getId() . '.controllers.login.GoogleAction',
			'yahoo'		=>	$this->module->getId() . '.controllers.login.YahooAction',
			'facebook'	=>	$this->module->getId() . '.controllers.login.FacebookAction',
		);
	}

	public function actionIndex()
	{
		$this->render('index');
	}
}

?>