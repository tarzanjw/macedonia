<?php

/**
* Class này phục vụ các thao tác với REST API
*
* Cấu hình các callers theo kiểu cấu hình component, cho {@link RESTCaller}
* <code>
* 	array(
* 		'callerName'=> array(), # caller's configuration
* 	)
* </code>
*
* @author Tarzan <hocdt85@gmail.com>
*
* @property array $callers
*/
class RestAPICallerComponent extends CComponent
{
	function init()
	{

	}

	/**
	* @var RESTCaller
	*/
	protected $_callers = array(
	);

	protected $_callerInstances = array(
	);


	/**
	* @return RESTCaller
	*/
	function getRESTCaller($callerName)
	{
		Yii::import('ext.common.RESTful.RESTCaller');
		if (!isset($this->_callerInstances[$callerName])) {
			assert('isset($this->_callers[$callerName])');

			$cfg = $this->_callers[$callerName];

			#if (preg_match('{^(\w+)://([\w_]+)}', $cfg['url'], $m)) $cfg['url'] = Yii::app()->$m[1]->getRegistry($m[2]);
			#if (preg_match('{^(\w+)://([\w_]+)}', $cfg['httpUsername'], $m)) $cfg['httpUsername'] = Yii::app()->$m[1]->getRegistry($m[2]);
			#if (preg_match('{^(\w+)://([\w_]+)}', $cfg['httpPassword'], $m)) $cfg['httpPassword'] = Yii::app()->$m[1]->getRegistry($m[2]);
			$this->_callerInstances[$callerName] = Yii::createComponent($cfg);
		}

		return $this->_callerInstances[$callerName];
	}

	function setCallers($value)
	{
		$this->_callers = CMap::mergeArray($this->_callers, $value);

		foreach ($this->_callers as & $c)
			if (!isset($c['class'])) $c['class'] = 'RESTCaller';
	}
}

?>
