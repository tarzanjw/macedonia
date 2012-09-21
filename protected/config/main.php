<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$cfg = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Tài khoản Vật Giá',

	'defaultController'=>'signIn',

	'sourceLanguage'=>'vi',
	'language'=>isset($_GET['_l']) ? $_GET['_l'] : 'vi',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.forms.*',
		'application.components.*',
		'ext.common.helpers.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'OpenID'=>array(
			'class'=>'application.modules.OpenID.OpenIDModule',
			'modules'=>array(
				'hq'=>array(
				),
			),
		),

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'1',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths'=>array(
	            'bootstrap.gii',
	        ),
		),

	),

	'controllerMap'=>array(
		'docs'=>array(
			'class'=>'ext.common.StaticArticleController.StaticArticleController',
			'rootPath'=>CODE_PATH.'/data/docs',
			'title'=>'VatGia SSO Documentations',
		),

    	'user'=>array(
        	'class'=>'ext.common.OpenIDUser.OpenIDUserController',
    	),
	),

	// application components
	'components'=>array(
		'bootstrap'=>array(
            'class'=>'ext.common.bootstrap.components.Bootstrap',
            #'responsiveCss'=>true,
            'yiiCss'=>false,
		),

		'email'=>array(
			'class'=>'EmailComponent',
		),

		'sms'=>array(
			'class'=>'SMSComponent',
		),

		'sentMessages'=>array(
			'class'=>'CPhpMessageSource',
			'basePath'=>CODE_PATH.'/data/sentMessages',
			'forceTranslation'=>true,
			'onMissingTranslation'=>function ($e) {
				$e->message = Yii::t($e->category, $e->message, $e->params, null, 'system');
				$e->handled = true;
			}
		),

		'user'=>array(
			'allowAutoLogin'=>true,
		),

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				'dang-nhap'=>'/SignIn',
				'dang-nhap'=>'/SignIn/index',
				'dang-nhap/<_a:\w+>'=>'/SignIn/<_a>',

				'dang-ky/<_a:\w+>'=>'/SignUp/<_a>',

				'dang-xuat/<_a:\w+>'=>'/SignOut/<_a>',

				'docs/<file:.*>'=>'/docs',

				'<_c:\w+>/'=>'<_c>/',
				'<_c:\w+>/<_a:\w+>'=>'<_c>/<_a>',
				'<_m:\w+>/<_c:\w+>/<_a:\w+>'=>'<_m>/<_c>/<_a>',
				'<_p1:\w+>/<_p2:\w+>/<_p3:\w+>/<_p4:\w+>'=>'<_p1>/<_p2>/<_p3>/<_p4>',
			),
		),

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=vgid',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'logo'=>'/images/logo.png',
	),
);

if (defined('YII_DEBUG') && YII_DEBUG) $cfg = CMap::mergeArray($cfg, require 'debug.php');

return $cfg;