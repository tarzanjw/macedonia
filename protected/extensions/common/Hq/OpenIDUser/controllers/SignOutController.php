<?php

class SignOutController extends UserBaseController
{
	function accessRules()
	{
		return array(
        	array('allow', 'actions'=>array('*')),
		);
	}

	public function actionIndex()
	{
		Yii::app()->user->logout();
		#$this->getUserComponent()->setReturnUrl(null);
		$this->returnLastUrl();
	}
}