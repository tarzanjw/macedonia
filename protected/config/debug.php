<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'components'=>array(
		'db'=>array(
			'enableProfiling'=>true,
			'enableParamLogging'=>true,
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'categories'=>'dev.email dev.sms',
					'logFile'=>'email.log',
				),
				array(
					'class'=>'CProfileLogRoute',
					'categories'=>'',
				),
			),
		),
	),
);