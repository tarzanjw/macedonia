<?php
/* @var $this SettingController */
?>

<?php
	$label = Yii::t('view','Tài khoản');
	switch(Yii::app()->controller->action->id){
    case 'index':
		$label = Yii::t('view','Tài khoản');
        break;
    case 'info':
        $label = Yii::t('view','Thông tin');
        break;
    case 'security':
        $label = Yii::t('view','Bảo mật');
        break;
    case 'products':
	    $label = Yii::t('view','Sản phẩm');
	    break;
	case 'activate':
        $label = Yii::t('view','Kích hoạt tài khoản');
   		break;		    
}			
?>
<?php $this->beginContent('//layouts/main', array()); ?>
	
	<div class="page-header">
		<h1 class="redtext"><?= Yii::t('view', 'Thiết lập tài khoản'); ?></h1>
		<div class="icon-chevron-right"></div>

		<h1 class="redtext"><?= $label; ?></h1>
	</div>

	<div class="row">
		<div class="span3"><?php include '_nav.php'; ?></div>
		<div class="span9"><?= $content; ?></div>
	</div>
</div><!-- content -->
<?php $this->endContent(); ?>