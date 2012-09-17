<?php

Yii::import('ext.common.RestAPICallerComponent');

/**
* Class này phục vụ các thao tác với EmailCentral. {@link http://hq.lab.baokim.vn/projects/email-central/}
*
* @author Tarzan <hocdt85@gmail.com>
*/
class EmailCentralComponent extends RestAPICallerComponent
{
	const EX_CODE__INVALID_EMAIL = 460;
	const EX_CODE__TEMPLATE_NOT_FOUND = 461;

	protected $_callers = array(
		'sendByTemplate'=>array(
			'class' => 'RESTCaller',
			'url'=>'http://email.x.baokim.vn/EmailRest/tpl',

			'httpUsername'=>'registry://emailCentral_httpUsername',
			'httpPassword'=>'registry://emailCentral_httpPassword',
		),
	);

	/**
	* Gửi một email thôgn qua template
	* Xem thêm tại {@link http://hq.lab.baokim.vn/projects/email-central/wiki/EmailRest_API}
	*
	* @param int $tpl template của email, xem thêm EmailCentralComponent::TPL_XXX
	* @param string $to id của item gắn liền với OTP
	* @param string|array $args các tham số của template,
	*
	* @throws RESTException trong trường hợp RESTCaller trả về mã HTTP != 200
	*/
	function sendByTemplate($tpl, $to, $args=array())
	{
		if(is_array($args)){
			$args_template = $args;
			$args = '';
			foreach($args_template as $key=>$value){
				$args = $args.$key.'='.$value.';';
			}
			$args = substr($args,0,-1);
		}

		$caller = $this->getRESTCaller('sendByTemplate');
		$response = $caller->post(array(
			'tpl'=>$tpl,
			'to'=>$to,
			'args'=>$args,
		));
		$code = $caller->getCode();
		if ($code != 200) throw new RESTException($caller);
		else return true;
	}
}

