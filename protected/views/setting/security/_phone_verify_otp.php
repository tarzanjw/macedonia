 <?php
 	$style = ($show_verify_otp == true)?'':'display:none';
 ?>
 <?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'change-phone-verify-otp',
	'type'=>'horizontal',
//	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array(
		'class'=>'well form-horizontal form-change-pass span12',
		'style'=>$style,
	),
)); ?>

   <legend><?= Yii::t('view', 'Nhập mã xác minh số điện thoại mới'); ?></legend>
	<?php echo $form->textFieldRow($formModel,'phone', array(
			'maxlength'=>255,
//			'name'=>'VerifyPhone[phone-show]',
			'placeholder'=>Yii::t('view', 'Số điện thoại mới'),
			'disabled' => 'disabled',
			)); ?>
	<?php echo $form->hiddenField($formModel,'phone', array(
			'maxlength'=>255,
//			'name'=>'VerifyPhone[phone]',
			'placeholder'=>Yii::t('view', 'Số điện thoại mới'),
			)); ?>	
	<?php echo $form->textFieldRow($formModel,'otp', array(
			'maxlength'=>255,
//			'name'=>'VerifyPhone[otp]',
			'placeholder'=>Yii::t('view', 'mã xác minh'),
			)); ?>			
    <div class="controls">
      <button type="submit" class="btn btn-primary"><?= Yii::t('view','Cập nhật'); ?></button>
    </div>
 
	<?php  $this->endWidget(); ?>

<script language="JavaScript">	

jQuery(function($) {
		$('#resend_sms_label').click(function(e) {
        	$('#captcha-form').fadeOut(200);
        	$('#captcha-form').hide();
        	$('#captcha-form').removeClass('email');
        	$('#captcha-form').fadeIn(200);
        	});
		});
		
			jQuery(function($) {
		
		$('#change_phone_label').click(function(e) {
        	$('#change-phone-verify-otp').toggle('fast');
        	return false;
		});			
	});
</script>
