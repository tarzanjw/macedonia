<?php

/**
* @property Facebook $facebook
*/
class FacebookAction extends CAction
{
	protected $appId = '105362742862932';
	protected $secret= '01d8435bf51911ef08185191b8dcfab0';
	
	/**
	* @var Facebook
	*/
	private $_facebook;
	
	function getFacebook()
	{           
		if (!isset($this->_facebook)) {
			Yii::import($this->controller->module->id . '.components.facebook-sdk.facebook', true); 
			
			$this->_facebook = new Facebook(array(
				'appId'	=>	$this->appId,
				'secret'=>	$this->secret,
			));
		}
		
		return $this->_facebook;
	}
	
	protected function oauth_step_gainAccess()
	{
		
	}
	
	protected function oauth_step_callback()
	{
		
	}
	
	function run()
	{
		$user = $this->facebook->getUser();
		
		if ($user) {
			var_dump($user);
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $this->facebook->api('/me');
			var_dump($user_profile);
			echo '<pre>', print_r($user_profile, true), '</pre>';
			die;
		} else {
			echo CHtml::link('Login', $this->controller->redirect($this->facebook->getLoginUrl()));	
		}
	}
}

?>
