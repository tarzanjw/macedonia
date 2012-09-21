<?php
	/** @var SsoController $this */
	Yii::app()->clientScript->reset();
?>


<html lang="<?= Yii::app()->language; ?>">
<head>
	<meta charset="utf-8">
	<link rel="icon" type="image/png" href="/images/favicon.png">
	<title><?= Yii::t('view', 'Tài khoản Vật Giá'); ?></title>
	<noscript><meta http-equiv="refresh" content="5; url='<?= htmlentities($cont); ?>'"></noscript>
</head>
<body onload="doRedirect()">
	<script language="JavaScript">
	<!--
	function doRedirect() {
//		console.log('done');
//		return;
		location.replace(<?= CJavaScript::encode($cont); ?>);
    }
	//-->
	</script>
	<?= Yii::t('view', 'Xin đợi một lát ...'); ?>

	<br>
	<?= CHtml::link('Continue', $cont); ?>

    <?php foreach ($this->ssoSites as $site): ?>
    <img src="<?= $site['setSID']; ?>?token=<?= rawurlencode($token); ?>&timestamp=<?= rawurlencode($timestamp); ?>" width="0" height="0" />
	<?php endforeach; ?>
</body>
</html>
