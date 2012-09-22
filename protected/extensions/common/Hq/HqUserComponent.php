<?php

/**
* Class này phục vụ các tác vụ liên quan đến việc kiểm tra quyền cho hệ thống HQ
*
* @author Tarzan <hocdt85@gmail.com>
*
* @property array $roles
*/
class HqUserComponent extends CWebUser
{
	protected $_roles;

	public $userARClass='OpenIdUser';

	public $rolesField='roles';
	public $ukField='email';

	function init()
	{
		parent::init();

		if ($this->userARClass == 'OpenIdUser')
			Yii::import('ext.Hq.openIDUserModule.models.OpenIdUser');
	}

	/**
	* Lấy ra danh sách roles của user hiện tại
	*
	* @return array
	*/
	function getRoles()
	{
		if (!isset($this->_roles)) {
			$this->_roles=array();
			if ($this->hasState($this->ukField)) {
				$user = CActiveRecord::model($this->userARClass)->findByAttributes(array($this->ukField=>$this->getState($this->ukField)));
				if (!is_null($user)) $this->_roles = $user->getAttribute($this->rolesField);
			}

			foreach ($this->_roles as &$r) $r=strtolower($r);
		}

		return $this->_roles;
	}

	/**
	* User hiện tại có role này không?
	*
	* @param string $role
	*
	* @return boolean
	*/
	function hasRole($role)
	{
		return in_array(strtolower($role), $this->getRoles());
	}

	function getCurrentIsAdmin()
	{
		$currentRole = $this->getRoles();
		if(in_array(strtolower('ADMIN'), $this->getRoles()))
		{
			return true;
		} else {
			return false;
		}
	}

	/**
	* @inheritdoc
	*
	* Trả về true nếu như user hiện tại có roles $operation
	*/
	public function checkAccess($operation,$params=array(),$allowCaching=true)
	{
		if ($this->hasRole($operation)) return true;

		return parent::checkAccess($operation, $params, $allowCaching);
	}
}