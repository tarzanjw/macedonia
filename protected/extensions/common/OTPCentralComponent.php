<?php

Yii::import('ext.common.RestAPICallerComponent');

/**
* Class này phục vụ các thao tác với OTPCentral. {@link http://hq.lab.baokim.vn/projects/otp-central/}
*
* @author Tarzan <hocdt85@gmail.com>
*/
class OTPCentralComponent extends RestAPICallerComponent
{
	protected $_callers = array(
		'send'=>array(
            'class' => 'RESTCaller',
			'url'=>'registry://otpCentralUrl_send',     //registry
		),
		'check'=>array(
            'class' => 'RESTCaller',
			'url'=>'registry://otpCentralUrl_check',
		),
	);

	/**
	* Gửi một tin OTP
	* Xem thêm tại {@link http://hq.lab.baokim.vn/projects/otp-central/wiki/Post_otp}
	*
	* @param int $kindId loại của OTP
	* @param string $itemId id của item gắn liền với OTP
	* @param string $receiver địa chỉ người nhận
	* @param array $args các tham số mở rộng cho nội dung MT
	* @param boolean $forceNew
	*
	* @throws RESTException trong trường hợp RESTCaller trả về mã HTTP != 200
	*/
	function send($kindId, $itemId, $receiver, $args = array(), $forceNew = false, $sync = false)
	{
		$caller = $this->getRESTCaller('send');

		$response = $caller->post(array(
			'kind_id'=>$kindId,
			'item_id'=>$itemId,
			'receiver'=>$receiver,
			'force_new'=>$forceNew,
			'args'=>$args,
			'sync'=>$sync,
		));
		$code = $caller->getCode();

		if ($code != 200) throw new RESTException($caller);
    	else return true;
	}


	/**
	* Kiểm tra xem 1 OTP có hợp lệ hay không
	*
	* Xem thêm tại {@link http://hq.lab.baokim.vn/projects/otp-central/wiki/Check_OTP}
	*
	* @param mixed $kindId
	* @param mixed $itemId
	* @param mixed $value
	* @param mixed $deleteOnCorrect
	*
	* @return boolean Mã OTP có hợp lệ hay không
	*/
	function check($kindId, $itemId, $value, $deleteOnCorrect=false)
	{
		$caller = $this->getRESTCaller('check');
		$response = $caller->post(array(
			'kind_id'=>$kindId,
			'item_id'=>$itemId,
			'value'=>$value,
			'del_on_correct'=>$deleteOnCorrect,
		));

		$code = $caller->getCode();
		if ($code == 404) return false;
		if ($code == 200) return true;

		throw new RESTException($caller);
	}
}

