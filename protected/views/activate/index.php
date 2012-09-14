<?php if(Yii::app()->user->hasFlash('verifySMS')): ?>
 
<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('verifySMS'); ?>
</div>
<div class="span2"> sdf</div>
<?php endif; ?>
<form action="" method="post">
	<input type="text" name="sms_code"/>
	<input type="submit" value="<?php
									echo Yii::t('view', 'Kích hoạt');
								?>">
</form>
<?php
  
?>
