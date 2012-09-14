<?php
/* @var $this SettingController */
?>

<?php $this->beginContent('//layouts/main', array()); ?>
<div class="row setting">
	<div class="page-header">
		<h1 class="redtext"><?= Yii::t('view', 'Thiết lập tài khoản'); ?></h1>
	</div>

	<div class="row">
		<div class="span3"><?php include '_nav.php'; ?></div>
		<div class="span9"><?= $content; ?></div>
	</div>
</div><!-- content -->
<?php $this->endContent(); ?>