<?php

class SignInController extends Controller
{
	public $layout = '//signIn/_layout';
	public $sessionLifetime = 3600; # a hour
	public $staySignedInSessionLifetime = 604800; # a week

	protected function onSignedIn()
	{
		$ru = $this->getLastUrl();

		if (!empty($ru)) $this->redirect($ru);
		else $this->redirect($this->createUrl('/setting'));
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

class AccountIdentify extends CUserIdentity
{
	const ERROR_NOT_ACTIVATED_YET 	= 101;
	const ERROR_LOCKED 				= 102;
	const ERROR_INCORRECT_USERNAME_OR_PASSWORD = 103;

	/**
	* @var Acc
	*/
	private $_acc;

	function authenticate()
	{
		/** @var Acc $acc */
		$acc = null;
		if (!is_null($phone = TextHelper::normalizePhoneNumber($this->username)))
			$acc = Acc::model()->findByAttributes(array('phone'=>$phone));
		elseif (!is_null($email = TextHelper::normalizeEmail($this->username)))
			$acc = Acc::model()->findByAttributes(array('email'=>$email));

		if (is_null($acc)) return self::ERROR_UNKNOWN_IDENTITY;

		$this->_acc = $acc;
		$this->setState('first_name', $acc->first_name);
		$this->setState('last_name', $acc->last_name);
		$this->setState('email', $acc->email);
		$this->setState('phone', $acc->phone);

		if ($acc->status == Acc::STATUS_LOCKED) return self::ERROR_LOCKED;
		if ($acc->status == Acc::STATUS_NOT_ACTIVATED_YET) return self::ERROR_NOT_ACTIVATED_YET;

		$auth = $acc->auth;
		if (empty($auth)) return self::ERROR_INCORRECT_USERNAME_OR_PASSWORD;

		if ($auth->password !== SecurityHelper::hashPassword($this->password, $auth->password_salt))
			return self::ERROR_INCORRECT_USERNAME_OR_PASSWORD;

		return self::ERROR_NONE;
	}

	function getId() { return !isset($this->_acc) ? null:$this->_acc->id; }
	function getName() { return !isset($this->_acc) ? null:($this->_acc->first_name.' '.$this->_acc->last_name); }
}