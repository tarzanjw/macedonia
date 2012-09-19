<?php

/**
 * @property string $email
 * @property string $name
 */
class OpenIDUserIdentity extends CBaseUserIdentity
{
	public $email;
	
	public $name;
	
	public function __construct($email, $name)
	{
		$this->email = $email;
		$this->name = $name;
	}
	
	/**
	* @inheritdoc
	* 
	*/
    public function authenticate()
    {
    	$this->setState('email', $this->email);
    	$this->setState('name', $this->name);
    	
        return self::ERROR_NONE;
    }
 
    public function getId()
    {
        return $this->email;
    }
}