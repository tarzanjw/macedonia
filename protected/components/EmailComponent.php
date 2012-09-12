<?php

/**
* Class này phục vụ các thao tác với email
*
* @author Tarzan <hocdt85@gmail.com>
*/
class EmailComponent extends CApplicationComponent
{
	public $messagesSourceName = 'sentMessages';

	/**
	* Gửi 1 email
	*
	* @param string $from
	* @param string $to
	* @param string $subject
	* @param string $content
	*/
	function send($from, $to, $subject, $content)
	{
		// TODO : phải gửi email thực

		$txt = <<<EMAIL

EMAIL+
FROM: {$from}
TO: {$to}
SUBJECT: {$subject}
CONTENT:
{$content}
---

EMAIL;

		Yii::log($txt, CLogger::LEVEL_INFO, 'dev.email');
	}

	/**
	* Gửi email kiểm tra tính sở hữu của địa chỉ email
	*
	* @param Acc $acc
	*/
	function sendVerifyEmail(Acc $acc)
	{
		$params = $acc->getAttributes();

		$params['verify_link'] = Yii::app()->controller->createAbsoluteUrl('/verify');

		$tmp = array();
		foreach ($params as $k=>$v) $tmp['{'.$k.'}'] = $v;
		$params = $tmp;

		$from = Yii::t('sent', 'email.verify.from', $params, $this->messagesSourceName);
		$to = Yii::t('sent', 'email.verify.to', $params, $this->messagesSourceName);
		$subject = Yii::t('sent', 'email.verify.subject', $params, $this->messagesSourceName);
		$content = Yii::t('sent', 'email.verify.content', $params, $this->messagesSourceName);

		$this->send($from, $to, $subject, $content);
	}
}

