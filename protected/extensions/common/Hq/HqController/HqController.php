<?php

/**
* Class này cho phép sử dụng các tính năng cơ bản của 1 hệ thống backend
*
* @property string $userComponentName
* @property string $assetsUrl
*
* @property boolean $useDefaultLayout
* @property string $globalbarViewfile
*/
class HqController extends CController
{
    protected $_useDefaultLayout = null;
    static protected $_originLayoutPath = null;
	protected $_globalbarViewfile = null;

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='column2';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public $pageHeader;

	protected $_userComponentName='hqUser';

	function setUserComponentName($v)
	{
		$this->_userComponentName = $v;
	}

	function getUserComponentName()
	{
		if (!isset($this->_userComponentName)) {
			$this->_userComponentName='user';

			$module = $this->module;

			while (!is_null($module)) {
				if (isset($module->userComponentName) || $module->canGetProperty('userComponentName')) {
					$this->_userComponentName=$module->userComponentName;
					break;
				}

				$module=$module->parentModule;
			}
		}

		return $this->_userComponentName;
	}

	function setUseDefaultLayout($v) { $this->_useDefaultLayout = $v; }
	function getUseDefaultLayout()
	{
    	if (is_null($this->_useDefaultLayout)) {
            $m = $this->module;
            while (!is_null($m) && !isset($m->useDefaultLayout)) $m = $m->parentModule;
            if (is_null($m)) $m = Yii::app();

            $this->_useDefaultLayout = isset($m->useDefaultLayout) ? $m->useDefaultLayout : true;
    	}

    	return $this->_useDefaultLayout;
	}

	function setGlobalbarViewfile($v) { $this->_globalbarViewfile = $v; }
	function getGlobalbarViewfile()
	{
    	if (is_null($this->_globalbarViewfile)) {
            $m = $this->module;
            $viewFile = null;
            while (!is_null($m)) {
                if ($m->params->hasProperty('hqGlobalBarViewFile')) {
					$viewFile = $m->params['hqGlobalBarViewFile'];
					break;
				}

                if (isset($m->hqGlobalBarViewFile)) {
					$viewFile = $m->hqGlobalBarViewFile;
					break;
                }

                $m = $m->parentModule;
            }

            if (empty($viewFile)) {
				$m = Yii::app();
				if ($m->params->hasProperty('hqGlobalBarViewFile')) {
					$viewFile = $m->params['hqGlobalBarViewFile'];
				}
            }

            $this->_globalbarViewfile = !empty($viewFile) ? (Yii::getPathOfAlias($viewFile).'.php') : (self::$_originLayoutPath.'/_global_bar.php');
    	}

    	return $this->_globalbarViewfile;
	}

	function init()
	{

		Yii::setPathOfAlias('hqLayouts', dirname(__FILE__).DIRECTORY_SEPARATOR.'layouts');

		if (!isset(self::$_originLayoutPath))
			self::$_originLayoutPath = empty($this->module) ? Yii::app()->getLayoutPath() : $this->module->getLayoutPath();

        if ($this->useDefaultLayout) {
        	$parent = empty($this->module) ? Yii::app() : $this->getModule();
        	$parent->setLayoutPath(Yii::getPathOfAlias('hqLayouts'));
		}

		if ($this->userComponentName!='user')
			Yii::app()->setComponent('user', Yii::app()->getComponent($this->userComponentName));
	}

	private $_assetUrl;

	function getAssetsUrl()
	{
		if (!isset($this->_assetUrl)) $this->_assetUrl = Yii::app()->assetManager->publish(dirname(__FILE__).DIRECTORY_SEPARATOR.'assets');

		return $this->_assetUrl;
	}

	function filters()
	{
		return array('accessControl');
	}

	function accessRules()
	{
		return array(
			array('allow',
				'roles'=>array('ADMIN'),
			),
			array('deny'),
		);
	}
}