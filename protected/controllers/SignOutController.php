<?php

class SignOutController extends Controller
{
    public $layout='//signUp/_layout';

	public function actionIndex()
	{
		Yii::app()->user->logout();
		$this->redirect($this->getLastUrl());
	}
}