<?php

/**
* @property string $gsn
*/
class SsoController extends Controller
{
	public $loadGUI = false;
    public $layout = false;

	public $ssoSites = array(
    	'site1'=>array(
        	'clearSID'=>'http://site1.x/VatgiaID/sso/clearSID',
        	'setSID'=>'http://site1.x/VatgiaID/sso/setSID',
    	),
    	'site2'=>array(
        	'clearSID'=>'http://site2.x/VatgiaID/sso/clearSID',
        	'setSID'=>'http://site2.x/VatgiaID/sso/setSID',
    	),
	);

	private $_gsn;
	
	function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('deny', 
					'actions'=>array('signIn'),
					'expression'=>array($this, 'checkAccess'),
		),
		);
	}
	
	public function checkAccess(VatgiaIDUser $user)
	{
		if(!empty($user->currAcc) && $user->currAcc->status == Acc::STATUS_NORMAL)
			return false;
		throw new CHttpException('invalid request',400);	
		return true;
	}
	
	
	function getGsn()
	{
        if (!isset($this->_gsn)) {
			if (isset($_COOKIE['_gsn'])) $this->_gsn = $_COOKIE['_gsn'];
			else $this->setGsn(SecurityHelper::generateSalt(8));
        }

        return $this->_gsn;
	}
	function setGsn($v) { setcookie('_gsn', $v, 86400); return $this->_gsn = $v;  }

	function actionSignIn($_cont)
	{
		$data = $this->getCurrentAccount()->getAttributes();
        $token = SSOHelper::encryptSetSIDRequest($data['email'], $this->getGsn(), $data['last_modified_time']);

        $this->render('signin', array(
        	'cont'=>$_cont,
        	'token'=>$token,
        	'timestamp'=>time(),
        ));
	}

    function actionSignOut($_cont)
    {
        $this->render('signout', array(
        	'cont'=>$_cont,
        	'gsn'=>$this->getGsn(),
        ));
    }
}