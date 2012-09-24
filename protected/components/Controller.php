<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property string $lastUrl
 * @property string $currentUrl
 */
class Controller extends CController
{
    public $loadGUI = true;

    function init()
    {
		parent::init();

		if ($this->loadGUI) {
			Yii::app()->getComponent('bootstrap');
		}
    }

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='column1';
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

	public $pageHeader='Page header';

	function getCurrentUrl()
	{
		return Yii::app()->getRequest()->getHostInfo().Yii::app()->getRequest()->getRequestUri();
	}

	public function getCureentUser()
	{
		$user_id = Yii::app()->user->id;
		if(empty($user_id))  return null;
		$userModel = Acc::model()->findByPk($user_id);
		return $userModel;
	}

	function getContUrl($defaultCont='')
	{
        return Yii::app()->getRequest()->getQuery('_cont', $defaultCont);
	}

	function doContinue($defaultUrl='')
	{
        $this->redirect($this->getContUrl());
	}

	private $_currentAccount;

	/**
	* @return Acc
	*/
	function getCurrentAccount()
	{
		if (!isset($this->_currentAccount)) {
    		$id = Yii::app()->user->id;
    		if (empty($id)) $this->_currentAccount = null;
    		else $this->_currentAccount = Acc::model()->findByPk($id);
		}

    	return $this->_currentAccount;
	}

	function createUrl($route, $params=array(),$ampersand='&')
	{
		if (!isset($params['_l'])) {
			$l = Yii::app()->getRequest()->getQuery('_l');
			if (!empty($l)) $params['_l'] = $l;
		}

		return parent::createUrl($route, $params, $ampersand);
	}
}