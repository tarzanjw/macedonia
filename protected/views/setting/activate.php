<style>
.captcha-form {
    border: medium none;
    padding: 20px;
    width: 300px;
}
#recaptcha_widget {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #E5E5E5;
    width: 300px;
}
.recaptcha_image_cover {
    border-bottom: 1px solid #E5E5E5;
    padding: 5px 0;
    text-align: center;
    width: 300px;
}
#recaptcha_image {
    height: 57px;
    margin-left: 0px;
}
</style>
<h2 class="redtext activate-label"><?= Yii::t('view', 'Kích hoạt tài khoản.'); ?></h2>
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));?>	
 	<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));?>
<div class="row-fluid">
	<?php

		if($accModel->is_phone_verified != 1):
	?>
	
	<div class="span6">
		<?php include 'activate/phone.php'; ?>
	</div>
	<?php
		endif;
	?>	
	<?php
		if($accModel->is_email_verified != 1):
	?>
	<div class="span6">
		<?php include 'activate/email.php'; ?>
	</div>
	<?php
		endif;
	?>  
</div>
  

<?php
	$style = 'display:none';
	if($captcha_error){
		$style = '';
	}
	$classKind = $kind=='email'?'email':'';
?>
<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'captcha-form',
	'htmlOptions'=>array(
		'class'=>'well captcha-form '.$classKind,
		'style'=>$style,
	),
)); ?>

	<?php echo $form->label($captchaModel, 'captcha'); ?>
	<?php $this->widget('ext.common.recaptcha.EReCaptcha', array(
		'model'=>$captchaModel,
		'attribute'=>'captcha',
		'theme'=>'custom',
		'language'=>Yii::app()->language,
		'publicKey'=>'6LddktYSAAAAAPzr2SXhxl5dJvKcQsW_zFn3Orp2',
	));?>
	<?= $form->error($captchaModel, 'captcha'); ?>
	 <button name="resend_sms" class="btn btn-primary btn-resend-sms" type="submit"><i class="icon-repeat"></i><?php
		echo Yii::t('view', 'Gửi lại mã');
	?></button>
	<input type="hidden" name="kind" id="kind" value="<?php
														echo $kind;
													?>">
	<?php $this->endWidget(); ?>

