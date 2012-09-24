 <?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'change-phone-form',
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
 <legend><?= Yii::t('view', 'Đổi số điện thoại'); ?></legend>
    <?php echo $form->passwordFieldRow($formModel,'password', array(
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu của bạn'),)); ?>	
	<?php echo $form->textFieldRow($formModel,'phone', array(
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Số điện thoại mới'), 
			)); ?>	
	
	<div class="control-group">
	<?php echo $form->label($formModel, 'captcha',array('class'=>'control-label  required')); ?>
		<div class="controls phone-captcha">
		<?php $this->widget('ext.common.recaptcha.EReCaptcha', array(
			'model'=>$formModel,
			'attribute'=>'captcha',
			'theme'=>'custom',
			'language'=>Yii::app()->language,
			'publicKey'=>'6Lc_YNYSAAAAAEEDPneMru1h_OQrrtQAcFrmBcOy'
		));
		?>
		<?= $form->error($formModel, 'captcha'); ?>
		</div>
	</div>
    <div class="controls">
      <button type="submit" class="btn btn-primary"><?= Yii::t('view','Cập nhật'); ?></button>
    </div>
    
<?php $this->endWidget(); ?>  

<script language="JavaScript">
	<!--
	jQuery(function($) {
		
		$('#change_phone_label').click(function(e) {
        	$('#change-phone-form').toggle('fast');
        	return false;
		});			
	});
		
	//-->

</script>