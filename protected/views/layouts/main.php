<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<link rel="icon" type="image/png" href="/images/favicon.png">-->

	<link rel="stylesheet" type="text/css" href="/css/screen.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body><div class="body-wrp">
	<?php #include '_global_bar.php'; ?>
	<div class="header-bar">
    	<div class="header content">
        	<img src="/images/logo.png" alt="Vật Giá">
    	</div>
	</div>

    <div class="container" style="padding-bottom: 100px;">
    	<?php if (!empty($this->pageHeader) || !empty($this->pageHeaderSubtext)): ?>
    	<div class="page-header redtext">
    		<h1>
           		<?php if (!empty($this->pageHeader)): ?><?php echo $this->pageHeader; ?><?php endif; ?>
	            <?php if (!empty($this->pageHeaderSubtext)): ?><small><?php echo $this->pageHeaderSubtext; ?></small><?php endif; ?>
    		</h1>
    	</div>
    	<?php endif; ?>

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