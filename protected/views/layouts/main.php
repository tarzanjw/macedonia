<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="/images/favicon.png">

	<?php Yii::app()->clientScript->registerCssFile('/css/screen.css'); ?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body><div class="body-wrp">
	<?php #include '_global_bar.php'; ?>
	<div class="header-bar">
    	<div class="header content">
        	<img class="logo" src="/images/logo.png" alt="Vật Giá">

        	<?php include '_member.php'; ?>
    	</div>
	</div>

    <div class="container" style="padding-bottom: 100px;">
		<?php echo $content; ?>
	</div>

	<?php include '_footer.php'; ?>
</div></body>
</html>