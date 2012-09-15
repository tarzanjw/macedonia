<?php

class ServerController extends Controller
{
	/**
	* @var BaoKimOpenIDProvider
	*/
	private $_openIDProvider;

	/**
	* @var Acc
	*/
	private $_currentAccount;

	private $_hasApproved=false;

	private $_attrFieldMap = array(
		'person/guid'				=> 'id',
		'namePerson/fullname'		=> 'fullname',
    	'namePerson/first'			=> 'first_name',
    	'namePerson/last'			=> 'last_name',
        'contact/email'				=> 'email',
        'contact/phone'				=> 'phone',
        'media/image'				=> 'avatar',
        'person/gender'				=> 'gender',
        'birthDate'					=> 'dob',
        'contact/postaladdress'		=>	'address',
        'contact/city'				=>	'city_id',
	);

	function getAttrLabels()
	{
		return array(
			'person/guid'				=> Yii::t('view', 'id'),
			'namePerson/fullname'		=> Yii::t('view', 'Họ tên'),
    		'namePerson/first'			=> Yii::t('view', 'Họ đệm'),
    		'namePerson/last'			=> Yii::t('view', 'Tên'),
	        'contact/email'				=> Yii::t('view', 'Email'),
	        'contact/phone'				=> Yii::t('view', 'Điện thoại di động'),
	        'media/image'				=> Yii::t('view', 'Avatar'),
	        'person/gender'				=> Yii::t('view', 'Giới tính'),
	        'birthDate'					=> Yii::t('view', 'Ngày sinh'),
	        'contact/postaladdress'		=> Yii::t('view', 'Địa chỉ'),
	        'contact/city'				=> Yii::t('view', 'Thành phố'),
		);
	}

	/**
	* @return Acc
	*/
	function getCurrentAccount()
	{
		if (!isset($this->_currentAccount)) {
			if (Yii::app()->user->isGuest) return null;

    		$id = Yii::app()->user->id;
			$this->_currentAccount = Acc::model()->findByPk($id);
		}

		return $this->_currentAccount;
	}

    protected function getRealmType($realm)
    {
        $c = new CDbCriteria();
        $c->compare('realm', $realm);
//        $c->addCondition('enable');

		/**
		* @var OpenIDRealm
		*/
		$r = OpenIDRealm::model()->find($c);

        return is_null($r) ? OpenIDRealm::TYPE_DEPEND_ON_ACCOUNT
        	: ($r->enable ? $r->type : OpenIDRealm::TYPE_DENY);
    }

	/**
	 * Checks whether an user is authenticated.
	 * The function should determine what fields it wants to send to the RP,
	 * and put them in the $attributes array.
	 * @param Array $attributes
	 * @param String $realm Realm used for authentication.
	 * @return String OP-local identifier of an authenticated user, or an empty value.
	 */
	function checkid($realm, &$attributes)
	{
		if (Yii::app()->user->isGuest) return false;

		$accId = Yii::app()->user->id;

		$acc = $this->getCurrentAccount();
        assert('!is_null($acc)');

        $approved = null;

        switch ($this->getRealmType($realm)) {
        	case OpenIDRealm::TYPE_ALLOW:
        		$approved = true;
        		break;
        	case OpenIDRealm::TYPE_DENY:
        		$approved = false;
        	default:
        		$approved = null;
        }

        if ($approved) {
			$attributes = array();
			foreach ($this->_attrFieldMap as $ax=>$sreg) {
	            if ($acc->hasAttribute($sreg))
            		$attributes[$ax] = $acc->$sreg;
			}

			return $this->getOpenIDProvider()->serverLocation.'?id='.$acc->id;
        }

        if ($approved === false) return false;

        return false;
	}

	protected function refineAttributes($attrs)
	{
		$acc = $this->getCurrentAccount();
		if (empty($acc)) return array();

		$attrLabels = $this->getAttrLabels();

    	$rs = array();
    	foreach ($this->_attrFieldMap as $ax=>$sreg) {
			if (!in_array($ax, $attrs)) continue;
			$value = $acc->getAttribute($sreg);
			if (empty($value)) continue;

			$rs[] = array(
            	'label'=>$attrLabels[$ax],
            	'name'=>$ax,
            	'value'=>$value,
			);
    	}

    	return $rs;
	}

	/**
     * Displays an user interface for inputting user's login and password.
     * Attributes are always AX field namespaces, with stripped host part.
     * For example, the $attributes array may be:
     * array( 'required' => array('namePerson/friendly', 'contact/email'),
     *        'optional' => array('pref/timezone', 'pref/language')
     * @param String $identity Discovered identity string. May be used to extract login, unless using $this->select_id
     * @param String $realm Realm used for authentication.
     * @param String Association handle. must be sent as openid.assoc_handle in $_GET or $_POST in subsequent requests.
     * @param Array User attributes requested by the RP.
     */
    function setup($identity, $realm, $assoc_handle, $attributes)
    {
    	if (Yii::app()->user->isGuest) {
			$this->redirect($this->createUrl('/SignIn', array('_ru'=>$this->currentUrl)));
			return;
    	}

        $this->render('setup', array(
        	'realm'=>$realm,
        	'assocHandle'=>$assoc_handle,
        	'requiredAttrs'=>$this->refineAttributes($attributes['required']),
        	'optionalAttrs'=>$this->refineAttributes($attributes['optional']),
        ));
    }


	/**
	* @return BaoKimOpenIDProvider
	*/
	function getOpenIDProvider()
	{
		if (!isset($this->_openIDProvider)) $this->_openIDProvider = new OpenIDProvider($this);

		return $this->_openIDProvider;
	}

	public function actionIndex()
	{
		$this->getOpenIDProvider()->server();
	}
}