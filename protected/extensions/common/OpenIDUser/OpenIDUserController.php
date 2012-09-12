<?php

require_once __DIR__.'/LightOpenID.php';

/**
* This class support all operations with OpenID
*
* <code>
CREATE TABLE open_id_user(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  name TINYTEXT NOT NULL,
  avatar TINYTEXT DEFAULT NULL,
  enable TINYINT(1) NOT NULL DEFAULT 1,
  created_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE INDEX UK_open_id_user__email (email)
)
ENGINE = INNODB
AUTO_INCREMENT = 1
CHARACTER SET utf8
COLLATE utf8_general_ci;
* </code>
*
* @property string $returnUrl the last url of User before he redirected to current url
* @property CWebUser $user
* @property string $modelClass
*/
class OpenIDUserController extends Controller
{
    public $requiredAttributes = array(
		'contact/email',
		'namePerson',
		'namePerson/first',
		'namePerson/last',
    );

    public $optionalAttributes = array(
		'contact/phone',
		'person/guid',
		'person/gender',
		'media/image/default',
		'company/name',
		'company/email',
		'person/website',
		'merchant/website',
    );

    public $attributesMapping = array(
		'contact/email'=>'email',
		'namePerson'=>'name',
//		'namePerson/first',
//		'namePerson/last',
		'contact/phone'=>'phone',
		'person/guid'=>'id',
		'person/gender'=>'gender',
		'media/image/default'=>'avatar',
//		'company/name',
//		'company/email',
//		'person/website',
//		'merchant/website',
    );

    private $_modelClass='ext.common.OpenIDUser.OpenIDUser';

    function setModelClass($v) { $this->_modelClass = $v; }
    function getModelClass()
    {
    	if (strpos($this->_modelClass, '.') !== false)
    		$this->_modelClass = Yii::import($this->_modelClass);

    	return $this->_modelClass;
    }

    public $userComponentName='user';

    function init()
    {
        parent::init();

        if ($this->userComponentName != 'user')
        	Yii::app()->setComponent('user', Yii::app()->{$this->userComponentName});
    }

    function getViewPath()
    {
		return __DIR__.'/views';
    }

    private $_assetsUrl;
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl =
            	Yii::app()->getAssetManager()->publish(
            		__DIR__.'/assets',
            		false,
            		-1,
            		false
            	);
        return $this->_assetsUrl;
    }

	private $_returnUrl;
	function getReturnUrl()
	{
        if (!isset($this->_returnUrl)) {
        	$x = Yii::app()->getRequest()->getQuery('ru');
            if (!empty($x)) return $this->_returnUrl = $x;

            $x = $this->getUser()->returnUrl;
            if (!empty($x)) return $this->_returnUrl = $x;

            $x = Yii::app()->getRequest()->getUrlReferrer();
            if (!empty($x)) return $this->_returnUrl = $x;

    		return $this->_returnUrl = Yii::app()->homeUrl;
        }

        return $this->_returnUrl;
	}

	/**
	* @return CWebUser
	*/
	function getUser()
	{
		return Yii::app()->user;
	}

	/**
	* Hàm này sẽ làm 2 bước sau, theo thứ tự ưu tiên từ cao đến thấp :
	* 	1. nếu cửa sổ hiện tại là popup ($_GET['popup]) thì đóng cửa sổ, refresh cửa sổ chính
	* 	2. redirect về địa chỉ cuối cùng
	*/
	function returnLastUrl()
	{
		if (isset($_GET['popup']) && $_GET['popup']) {
			$this->renderPartial('close_popup');
			return;
		}
		$this->redirect($this->getReturnUrl());
	}

	function actions()
	{
		return array(
        	'signin'=>'OpenIDSignInAction',
        	'admin'=>'OpenIDAdminAction',
		);
	}

	function actionSignout()
	{
    	$lastUrl = $this->returnUrl;

        $this->getUser()->logout();
        $this->redirect($lastUrl);
	}
}

class OpenIDUserIdentity extends CBaseUserIdentity
{
	public $email;

	public $name;

	public $avatar;

	public function __construct($attrs)
	{
        if (isset($attrs['email'])) $this->email = $attrs['email'];
        if (isset($attrs['name'])) $this->name = $attrs['name'];
        if (isset($attrs['avatar'])) $this->avatar = $attrs['avatar'];

        if (empty($this->avatar)) $this->avatar = 'http://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email)));
	}

	/**
	* @inheritdoc
	*
	*/
    public function authenticate()
    {
    	$this->setState('email', $this->email);
    	$this->setState('name', $this->name);
    	$this->setState('avatar', $this->avatar);

        return self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->email;
    }
}

class OpenIDSignInAction extends CAction
{
    protected $_providers = array(
    	'google'=>'https://www.google.com/accounts/o8/id',
    	'yahoo'=>'http://open.login.yahooapis.com/openid20/www.yahoo.com/xrds',
    	'baokim'=>'http://x.x/openid/server',
    );

	protected function doGainAccess($openIDIdentity)
	{
		$openid = new LightOpenID();
		$openid->identity = $openIDIdentity;

		$openid->required = $this->getController()->requiredAttributes;
		$openid->optional = $this->getController()->optionalAttributes;

		$get = $_GET;
		$get['step'] = 'callback';
		$openid->returnUrl = $this->getController()->createAbsoluteUrl($this->getId(), $get);

		$url = $openid->authUrl();
		$this->getController()->redirect($url);
	}

	protected function normalizeAttributes($attrs)
	{
		$nAttrs = array();

		if (!isset($attrs['namePerson']) || empty($attrs['namePerson'])) {
			$e = array();
			if (isset($attrs['namePerson/first']) && !empty($attrs['namePerson/first']))
				$e[] = $attrs['namePerson/first'];
			if (isset($attrs['namePerson/last']) && !empty($attrs['namePerson/last']))
				$e[] = $attrs['namePerson/last'];

			if (!empty($e)) $attrs['namePerson'] = implode(' ', $e);
		}

		$mapping = $this->getController()->attributesMapping;
		foreach ($attrs as $name=>$value) {
			if (isset($mapping[$name])) $nAttrs[$mapping[$name]] = $value;
		}

		return $nAttrs;
	}

	protected function saveUserInfo($attrs)
	{
		$className = $this->getController()->modelClass;

        $model = new $className();
        $model->setAttributes($attrs);

        if (!is_null(CActiveRecord::model($className)->findByAttributes(array('email'=>$attrs['email'])))) return;

        if (!$model->save()) throw new CHttpException(500, print_r($model->getErrors(), true));
	}

	protected function doCallback()
	{
		if (Yii::app()->getRequest()->getQuery('openid_mode') !== 'id_res') return $this->controller->returnLastUrl();

		$openid = new LightOpenID();

		if ($openid->validate()) {
			$attrs = $this->normalizeAttributes($openid->getAttributes());

			$user = new OpenIDUserIdentity($attrs);
			$user->authenticate();

			$this->saveUserInfo($attrs);
			Yii::app()->user->login($user);

			$this->getController()->returnLastUrl();
		}
		else {
			throw new CHttpException(501, "Invalid openid operation");
		}
	}

	function run($p=null, $step = 'gain_access')
	{
        if (!Yii::app()->user->isGuest)
        	return $this->getController()->returnLastUrl();

		if (empty($p)) {
	        $this->getController()->render('signin', array());
	        return;
		}

        if (!isset($this->_providers[$p])) throw new CHttpException('Invalid provider', 400);

        switch ($step) {
			case 'gain_access' :
				$this->doGainAccess($this->_providers[$p]);
				break;
			case 'callback' :
				$this->doCallback();
				break;
			default:
				throw new CHttpException(400, 'Invalid step');
		}
	}
}

class OpenIDAdminAction extends CAction
{
    protected function enableUser($email, $enable)
    {
    	$modelClass = $this->getController()->modelClass;

    	$model = CActiveRecord::model($modelClass)->findByAttributes(array('email'=>$email));
    	if (is_null($model)) return;

    	$model->enable = $enable;
		$model->save();
    }

    function run()
    {
        if (isset($_POST['btnEnable']) && !empty($_POST['btnEnable'])) $this->enableUser($_POST['btnEnable'], true);
        if (isset($_POST['btnDisable']) && !empty($_POST['btnDisable'])) $this->enableUser($_POST['btnDisable'], false);

    	$modelClass = $this->getController()->modelClass;
 		$model=new $modelClass('search');

        $model->unsetAttributes();  // clear any default values
        if(isset($_GET[$modelClass]))
            $model->attributes=$_GET[$modelClass];

        $this->getController()->render('admin',array(
            'model'=>$model,
        ));
    }
}