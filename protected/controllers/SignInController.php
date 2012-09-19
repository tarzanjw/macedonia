<?php

class SignInController extends Controller
{
	public $layout = '//signIn/_layout';
	public $sessionLifetime = 3600; # a hour
	public $staySignedInSessionLifetime = 604800; # a week

	protected function onSignedIn()
	{
		$this->redirect($this->createUrl('/sso/signIn', array('_cont'=>$this->getContUrl($this->createUrl('/setting')))));
	}

	protected function doSignIn(SignInForm $form)
	{
		$identify = new AccountIdentify($form->username, $form->password);

		if (($code =$identify->authenticate()) == AccountIdentify::ERROR_NONE) {
			$lifetime = $form->remember ? $this->staySignedInSessionLifetime:$this->sessionLifetime;
			Yii::app()->user->login($identify, $lifetime);
			$this->onSignedIn();
			return true;
		}

		switch ($code) {
			case AccountIdentify::ERROR_NOT_ACTIVATED_YET:
				$msg = Yii::t('view', 'Tài khoản chưa kích hoạt.'); break;
			case AccountIdentify::ERROR_LOCKED:
				$msg = Yii::t('view', 'Tài khoản đã bị khóa.'); break;
			case AccountIdentify::ERROR_INCORRECT_USERNAME_OR_PASSWORD:
				$msg = Yii::t('view', 'Thông tin đăng nhập không đúng.'); break;
			case AccountIdentify::ERROR_UNKNOWN_IDENTITY:
				$msg = Yii::t('view', 'Tài khoản không tồn tại trên hệ thống.'); break;
			default:
				assert(false);
		}

		$form->addError('password', $msg);
	}

	public function actionIndex()
	{
		/** @var CWebUser */$user = Yii::app()->user;

		if (!$user->getIsGuest()) return $this->_reauthenticate();
		else return $this->_authenticate();
	}

	protected function _reauthenticate()
	{
		/** @var CWebUser */$user = Yii::app()->user;
		$model = new SignInForm('signin');

		if (!empty($user->email)) $username = $user->email;
		elseif (!empty($user->phone)) $username = $user->phone;

		if (isset($_POST['SignInForm'])) {
			$model->setAttributes($_POST['SignInForm'], false);
			$model->username = $username;
			if ($model->validate()) $this->doSignIn($model);
		}

		$model->username = $username;

		$this->render('reauth', array(
			'model'=>$model,
		));
	}

	protected function _authenticate()
	{
		$model = new SignInForm('signin');
		if (isset($_POST['SignInForm'])) {
			$model->setAttributes($_POST['SignInForm'], false);
			if ($model->validate()) $this->doSignIn($model);
		}

		$this->render('auth', array(
			'model'=>$model,
		));
	}
}