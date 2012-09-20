 <?php
 	$style = ($is_validate_phone == true)?'display:none':'';
 ?>
 <?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'change-phone-form',
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
    <?php echo $form->passwordFieldRow($changePhoneFormModel,'password', array(
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu của bạn'),)); ?>	
	<?php echo $form->textFieldRow($changePhoneFormModel,'phone', array(
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Số điện thoại mới'), 
			)); ?>			
    <div class="controls">
      <button type="submit" class="btn"><?= Yii::t('view','Cập nhật'); ?></button>
    </div>
<?php $this->endWidget(); ?>  

<script language="JavaScript">
	<!--
	jQuery(function($) {
		
		$('#change_phone_label').click(function(e) {
        	$('#change-phone-form').toggle('fast');
		});			
	});
		
	//-->

</script>