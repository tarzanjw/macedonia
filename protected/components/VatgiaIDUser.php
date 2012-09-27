<?php

class VatgiaIDUser extends CWebUser
{
    public $_currAcc;
    
	function getCurrAcc()
	{
    	if (!isset($this->_currAcc)) {
    		$id = Yii::app()->user->id;
    		if (empty($id)) $this->_currAcc = null;
    		else $this->_currAcc = Acc::model()->findByPk($id);
		}

    	return $this->_currAcc;
	}
	
	function hasActivated()
	{
		$currAcc = $this->currAcc;
		if(!empty($currAcc) && $currAcc->status == Acc::STATUS_NORMAL)
			return true;
		return false;
	}
}