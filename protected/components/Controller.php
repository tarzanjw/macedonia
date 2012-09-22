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
	public $pageHeaderSubtext='';

	function getCurrentUrl()
	{
		return Yii::app()->getRequest()->getHostInfo().Yii::app()->getRequest()->getRequestUri();
	}
	
	public function getCurrentUser()
	{
		$user_id = Yii::app()->user->id; 
		if(empty($user_id))  return null;
		$userModel = Acc::model()->findByPk($user_id);
		return $userModel;
	}

	private $_lastUrl;
	function getLastUrl()
	{
		if (!isset($this->_lastUrl)) {
			$ru = Yii::app()->getRequest()->getQuery('_ru');
			if (empty($ru)) {
				$ru = Yii::app()->getRequest()->getUrlReferrer();
				if (empty($ru)) {
					$ru = Yii::app()->user->getReturnUrl('@');
					if ($ru == '@') $ru = '';
				}
			}

			if (strpos($ru, 'http') !== 0) $ru = Yii::app()->getRequest()->getHostInfo().$ru;
			if ($ru == $this->getCurrentUrl()) $this->_lastUrl = '';
			else $this->_lastUrl = $ru;
		}

		return $this->_lastUrl;
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