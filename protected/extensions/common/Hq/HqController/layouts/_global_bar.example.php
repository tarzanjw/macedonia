<?php
	$this->widget('bootstrap.widgets.BootNavbar', array(
	'fixed'=>false,
	'brand'=>'Bank HQ',
	'brandUrl'=>'/hq/',
	#'collapse'=>true, // requires bootstrap-responsive.css
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.BootMenu',
			'items'=>array(
				array('label'=>Yii::t('label', 'Giao dịch'), 'url'=>array('/hq/bankTransaction/')),
				array('label'=>'Kiểm tra phiếu thu', 'url'=>array('/hq/bankTransaction/verify')),
				'----',
				array('label'=>'Truy soát ngân hàng', 'url'=>'#', 'items'=>array(
                    array('label'=>'Smartlink', 'url'=>array('/hq/smlTransaction')),
                    array('label'=>'TechcomBank', 'url'=>array('/hq/tcbTransaction')),
				)),
			),
		),
		'<form method="get" class="navbar-search pull-left">
	<input type="hidden" name="r" value="search">
	<input type="text" class="search-query" name="" value="" placeholder="Keyword to search">
</form>',
		array(
			'class'=>'bootstrap.widgets.BootMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items'=>array(
				array('label'=>Yii::t('label', 'Tài khoản quản trị'), 'url'=>array('/hq/user/role')),
				array('label'=>Yii::t('label', 'System'), 'url'=>array('#'), 'items'=>array(
                    array('label'=>'Registry', 'url'=>array('/hq/registry')),
                    '----',
                    array('label'=>'Bank Group', 'url'=>array('/hq/bankGroup')),
                    array('label'=>'Bank', 'url'=>array('/hq/bank')),
                    '----',
                    array('label'=>'Payment Method', 'url'=>array('/hq/paymentMethod')),
                    array('label'=>'Bank Payment Method', 'url'=>array('/hq/bankPaymentMethod')),
                    array('label'=>'Payment Gate', 'url'=>array('/hq/paymentGate')),
				)),
				array('label'=>Yii::t('label', 'Log'), 'url'=>array('#'), 'items'=>array(
                    array('label'=>'TCB Api Log', 'url'=>array('/hq/tcbApiLog')),
                    array('label'=>'Core Api Log', 'url'=>array('/hq/coreRestApiLog')),
                    '----',
                    array('label'=>'Yii Log', 'url'=>array('/hq/yiiLog')),
				)),
				'----',
				array('label'=>'Đăng xuất ('.Yii::app()->hqUser->name.')', 'url'=>array('/hq/user/signOut', 'ru'=>Yii::app()->request->requestUri), 'visible'=>!Yii::app()->hqUser->isGuest),
				array('label'=>'Đăng nhập', 'url'=>array('/hq/user/signIn', 'ru'=>Yii::app()->request->requestUri), 'visible'=>Yii::app()->hqUser->isGuest),
			),
		),
	),
)); ?>