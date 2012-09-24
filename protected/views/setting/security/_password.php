<?php
	/** @var ChangePassForm $modelPass */
	$modelPass = $changePassFormModel;
//	$style = ($is_validate_pass == true)?'display:none':'';
?>
<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'changePass-form',
	'type'=>'horizontal',
//	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array(
		'class'=>'well form-change-pass1',
	),
)); ?>
	<legend><?= Yii::t('view', 'Đổi mật khẩu'); ?></legend>
	
	<?= $form->passwordFieldRow($modelPass, 'oldPassword',array(
		'placeholder'=>Yii::t('view', 'Mật khẩu hiện tại'), 
	)); ?>	
		
	<?php echo $form->passwordFieldRow($modelPass,'newPassword', array(
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu mới'),
	)); ?>	
	
	<?php echo $form->passwordFieldRow($modelPass,'confirmed_password', array(
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Xác nhận mật khẩu mới'), 
	)); ?>	
  
    <div class="form-actions">
		<button type="submit" class="btn btn-primary"><?= Yii::t('view', 'Thay đổi'); ?></button>
		<a href="<?= $this->createUrl(''); ?>" class="btn"><?= Yii::t('view', 'Hủy bỏ'); ?></a>
	</div>

<?php $this->endWidget(); ?>

