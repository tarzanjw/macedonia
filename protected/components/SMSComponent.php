<?php

/**
* Class này phục vụ các thao tác với sms
*
* @author Tarzan <hocdt85@gmail.com>
*/
class SMSComponent extends CApplicationComponent
{
	public $messagesSourceName = 'sentMessages';
	public $brandName = 'VATGIA';

	/**
	* Gửi 1 sms
	*
	* @param string $from
	* @param string $to
	* @param string $subject
	* @param string $content
	*/
	function send($to, $content)
	{
		// TODO : phải gửi sms thực

		$txt = <<<SMS

SMS+
TO: {$to}
CONTENT:
{$content}
---

SMS;

		Yii::log($txt, CLogger::LEVEL_INFO, 'dev.sms');
	}

	/**
	* Gửi email kiểm tra tính sở hữu của địa chỉ email
	*
	* @param Acc $acc
	*/
	function sendVerifySMS(Acc $acc)
	{
		$params = $acc->getAttributes();

		$params['activation_code'] = SecurityHelper::generateSalt(6);

		$tmp = array();
		foreach ($params as $k=>$v) $tmp['{'.$k.'}'] = $v;
		$params = $tmp;

		$to = Yii::t('sent', 'sms.verify.to', $params, $this->messagesSourceName);
		$content = Yii::t('sent', 'sms.verify.content', $params, $this->messagesSourceName);

		$this->send($to, $content);
	}
}

