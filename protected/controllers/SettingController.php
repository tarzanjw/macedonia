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
}