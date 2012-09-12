<?php $this->widget('bootstrap.widgets.TbNavbar', array(
//		    'type'=>'inverse',  null or 'inverse'
	'fixed'=>'bottom',
	'brand'=>Yii::app()->name,
	'brandUrl'=>'/',
	'collapse'=>true, // requires bootstrap-responsive.css
	'items'=>array(
		array(
		    'class'=>'bootstrap.widgets.TbMenu',
		    'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
		    ),
		),
//		        '<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
		array(
		    'class'=>'bootstrap.widgets.TbMenu',
		    'htmlOptions'=>array('class'=>'pull-right'),
		    'encodeLabel'=>false,
		    'items'=>array(
		        array('label'=>'Sign In', 'url'=>array('/user/signin'), 'visible'=>Yii::app()->user->isGuest),
		        array(
		        	'label'=>
		        		(Yii::app()->user->isGuest ? '' :
		        			CHtml::image(Yii::app()->user->avatar.'?s=20', Yii::app()->user->name, array('style'=>'width:20px;')).' ')
		        		.Yii::app()->user->name,
		        	'url'=>'#',
		        	'items'=>array(
		        		array('label'=>'Manage Users', 'url'=>array('/user/admin')),
			            '---',
			            array('label'=>'Sign Out', 'url'=>array('/user/signout')),
			        ),
			    	'visible'=>!Yii::app()->user->isGuest
			    ),
		    ),
		),
	),
)); ?>