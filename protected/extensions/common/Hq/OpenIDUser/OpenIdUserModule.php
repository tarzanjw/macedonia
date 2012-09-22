<?php

class OpenIdUserModule extends CWebModule
{
	private $_assetsUrl;

	public $defaultController = 'signIn';

	public $userComponentName = 'user';

	public $roles = array(

	);

	public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null)
            $this->_assetsUrl =
            	Yii::app()->getAssetManager()->publish(
            		Yii::getPathOfAlias($this->getId() . '.assets'),
            		false,
            		-1,
            		true
            	);
        return $this->_assetsUrl;
    }

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			$this->id.'.models.*',
			$this->id.'.components.LightOpenID',
			$this->id.'.controllers.UserBaseController',
		));

        $parent = $this->getParentModule();
        if (empty($parent)) $parent = Yii::app();

        $this->layoutPath = $parent->layoutPath;
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
