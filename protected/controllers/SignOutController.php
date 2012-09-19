<?php

class SignOutController extends Controller
{
    public $layout='//signUp/_layout';

	public function actionIndex()
	{
		Yii::app()->user->logout();
		$this->redirect($this->createUrl('/sso/signOut', array('_cont'=>$this->getContUrl($this->createUrl('/signIn')))));
	}
}