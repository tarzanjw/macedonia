<!doctype html>
<html lang="<?php echo Yii::app()->language ?>">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="<?php echo Yii::app()->language; ?>" name="language">
</head>
<body>
	<div id='globalbar'>
		<?php if (is_file($this->globalBarViewfile)): ?>
        	<?php include $this->globalBarViewfile; ?>
        <?php else: ?>
            <h3><?php echo  $this->globalBarViewfile; ?></h3>
		<?php endif; ?>
	</div>

	<div class="container-fluid">
		<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'homeLink'=>CHtml::link('Home', array($this->id.'/')),
				'links'=>$this->breadcrumbs,
			)); ?><!-- breadcrumbs -->
		<?php endif?>

		<?php if (!empty($this->pageHeader)): ?>
		<!--<div class="page-header">
			<h1><?= $this->pageHeader; ?></h1>
		</div>-->
		<legend><?= $this->pageHeader; ?></legend>
		<?php endif; ?>

		<?php echo $content; ?>
	</div>

	<hr>
	<footer>
		<div class="container copy">
			&copy; 2012 by Bảo Kim JSC.
		</div>
	</footer>
</body>
</html>