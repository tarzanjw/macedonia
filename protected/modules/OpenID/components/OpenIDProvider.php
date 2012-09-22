<?php

Yii::import('ext.LightOpenIDProvider.LightOpenIDProvider');

class OpenIDProvider extends LightOpenIDProvider
{
    /**
    * Cho phép mọi user có thể đăng nhập
    *
    * @var boolean
    */
	public $select_id = true;

    # AX <-> SREG transform
    protected $ax_to_sreg = array(
        'namePerson/friendly'		=> 'nickname',
        'namePerson'				=> 'fullname',
    	'namePerson/first'			=> 'first_name',
    	'namePerson/last'			=> 'last_name',
        'contact/email'				=> 'email',
        'contact/phone'				=> 'phone',
        'media/image'				=> 'avatar',
        'person/gender'				=> 'gender',
        'birthDate'					=> 'dob',
        'contact/postaladdress'		=>	'address',
        'contact/city'				=>	'city',
    );

	/**
	* @var OpenIDController
	*/
    private $_controller;

	function __construct(CController $controller)
	{
    	parent::__construct();

    	$this->_controller = $controller;
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
    	return $this->_controller->checkId($realm, $attributes);
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
    	return $this->_controller->setup($identity, $realm, $assoc_handle, $attributes);
    }

    function assoc_handle()
    {
    	return sha1(microtime());
    }

    function setAssoc($handle, $data)
    {
    	$assoc = OpenIDAssoc::model()->findByAttributes(array('handle'=>$handle));
    	if (is_null($assoc)) $assoc = new OpenIDAssoc();

        $assoc->handle = $handle;
        $assoc->data = serialize($data);
        $assoc->expired_time = new CDbExpression('ADDDATE(CURRENT_TIMESTAMP, INTERVAL :lifetime SECOND)', array(
        	':lifetime'=>$this->assoc_lifetime,
        ));
        $assoc->save(false);
    }

    function getAssoc($handle)
    {
    	$assoc = OpenIDAssoc::model()->findByAttributes(array('handle'=>$handle));

    	return is_null($assoc) ? false : unserialize($assoc->data);
    }

    function delAssoc($handle)
    {
        OpenIDAssoc::model()->deleteAllByAttributes(array('handle'=>$handle));
    }

    function denyAccessing()
    {
		return $this->cancel();
    }
}