<?php

Yii::import('ext.Hq.HqController.HqController');

class UserBaseController extends HqController
{
    public $layout = 'column1';

	private $_returnUrl = null;

	/**
	* Lấy URL trả về khi thực hiện các thao tác login, log out
	*
	*/
	function getReturnUrl()
	{
		$user = Yii::app()->user;
		if (empty($this->_returnUrl)) {
			$this->_returnUrl = isset($_GET['ru']) ?
									$_GET['ru']
									:
									(!empty($user->returnUrl) && ($user->returnUrl != Yii::app()->request->requestUri) ?
										$user->returnUrl
										:
										Yii::app()->homeUrl);
		}

		return $this->_returnUrl;
	}

	/**
	* Hàm này sẽ làm 2 bước sau, theo thứ tự ưu tiên từ cao đến thấp :
	* 	1. nếu cửa sổ hiện tại là popup ($_GET['popup]) thì đóng cửa sổ, refresh cửa sổ chính
	* 	2. redirect về địa chỉ cuối cùng
	*
	*/
	function returnLastUrl()
	{
		if (isset($_GET['popup']) && $_GET['popup']) {
			$this->renderPartial('close_popup');
			return;
		}
		$this->redirect($this->getReturnUrl());
	}
}

?>
