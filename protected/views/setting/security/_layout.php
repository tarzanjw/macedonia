<?php $this->beginContent('application.views.setting._layout'); ?>
<?php /* @var $this SettingController */ ?>
	<?php $tab = $this->action->currentTab; ?>

	<?php $this->widget('bootstrap.widgets.TbMenu', array(
	    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
	    'stacked'=>false, // whether this is a stacked menu
	    'items'=>array(
	        array('label'=>Yii::t('view','Tổng quan'), 'url'=>array('', 'tab'=>'general'), 'active'=>$tab=='general'),
	        array('label'=>Yii::t('view','Mật khẩu'), 'url'=>array('', 'tab'=>'password'), 'active'=>$tab=='password'),
	        array('label'=>Yii::t('view','Số điện thoại'), 'url'=>array('', 'tab'=>'phone'), 'active'=>$tab=='phone'),
	        array('label'=>Yii::t('view','Câu hỏi bảo mật'), 'url'=>array('', 'tab'=>'question'), 'active'=>$tab=='question'),
	    ),
	)); ?>
	
	<?php $this->widget('bootstrap.widgets.TbAlert', array(
	'block'=>true, // display a larger alert block?
	'fade'=>true, // use transitions?
	'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
	'alerts'=>array( // configurations per alert type
		'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
	),
));?>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
	'block'=>true, // display a larger alert block?
	'fade'=>true, // use transitions?
	'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
	'alerts'=>array( // configurations per alert type
		'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
	),
));?>

<?= $content; ?>

<?php $this->endContent(); ?>