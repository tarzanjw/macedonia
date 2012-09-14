<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<link rel="icon" type="image/png" href="/images/favicon.png">-->

	<?php Yii::app()->clientScript->registerCssFile('/css/screen.css'); ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body><div class="body-wrp">
	<?php #include '_global_bar.php'; ?>
	<div class="header-bar">
    	<div class="header content">
        	<img src="/images/logo.png" alt="Vật Giá">
        	<?php if (isset($customButtons)): ?>
        	<div class="pull-right">
				<?= $customButtons; ?>
        	</div>
        	<?php endif; ?>
    	</div>
	</div>

    <div class="container" style="padding-bottom: 100px;">
    	<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'homeLink'=>array('label'=>'Home', 'url'=>array('/')),
				'links'=>$this->breadcrumbs,
			)); ?><!-- breadcrumbs -->
		<?php endif?>

		<?php echo $content; ?>

	</div>

	<?php include '_footer.php'; ?>
</div></body>
</html>