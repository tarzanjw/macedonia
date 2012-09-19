<?php

/**
* @property CWebUser $userComponent
*/
class OpenIDAction extends CAction
{
	protected $openIDIdentity;
	protected $sourceIdentify;
	
	/**
	* @return CWebUser
	*/
	function getUserComponent()
	{
		return Yii::app()->user;
		return $this->getController()->getUserComponent();
	}
	
	protected function doGainAccess()
	{
		$openid = new LightOpenID();
		$openid->identity = $this->openIDIdentity;
		
		$openid->required = array('contact/email', 'namePerson', 'namePerson/first', 'namePerson/last', 'person/gender', 'media/image/default');

		$get = $_GET;
		$get['step'] = 'callback';
		$openid->returnUrl = $this->getController()->createAbsoluteUrl($this->getId(), $get);

		$url = $openid->authUrl();
		$this->getController()->redirect($url);
	}
	
	protected function analyzeData($data)
	{
		$m = array();
		$m['email'] = $data['contact/email'];
		if (isset($data['namePerson'])) {
			$m['name'] = $data['namePerson'];
		} else {			
			$first_name = isset($data['namePerson/first']) ? $data['namePerson/first'] : '';
			$last_name = isset($data['namePerson/last']) ? $data['namePerson/last'] : '';
			
			$m['name'] = $first_name . ' ' . $last_name;
		}
		
		return $m;
	}
	
	protected function saveUserInfo(OpenIDUserIdentity $user)
	{
		$x = OpenIdUser::model()->findByAttributes(array('email'=>$user->email));
		if (is_null($x)) {
			$x = new OpenIdUser('create');	
			$x->roles = array();
		}
		
		$x->email = $user->email;
		$x->name = $user->name;
		
		$x->save();
	}
		
	protected function doCallback()
	{
		if (!isset($_GET['openid_mode']) || $_GET['openid_mode'] !== 'id_res') return $this->controller->returnLastUrl();
		
		$openid = new LightOpenID();

		if ($openid->validate()) {
			$m = $this->analyzeData($openid->getAttributes());
			
			$user = new OpenIDUserIdentity($m['email'], $m['name']);
			$user->authenticate();

			$this->saveUserInfo($user);			
			$this->getUserComponent()->login($user);
			
			$this->getController()->processAfterLogIn($user);
		}
		else throw new CHttpException(501, "Invalid openid operation");
	}
	
	function run($step = 'gain_access')
	{
		switch ($step) {
			case 'gain_access' :
				$this->doGainAccess();
				break;
			case 'callback' :
				$this->doCallback();
				break;
			default:
				throw new CHttpException(404, 'This step is invalid');
		}	
	}
}

?>
