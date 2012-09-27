<?php

/* @var $this SignUpController */
/* @var $model SignUpForm */

?>

<div class="well"><div class="row-fluid">
<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'acc-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'htmlOptions'=>array(

	),
)); ?>

	<h2>
		<?= Yii::t('view', 'Đăng nhập'); ?>
		<img src="<?= Yii::app()->params->logo; ?>" alt="<?= Yii::app()->name; ?>" width="90px" class="pull-right">
	</h2>

	<?php #echo $form->errorSummary($model); ?>

	<div class="row-fluid">
	<?php echo $form->textFieldRow($model,'username', array(
			'class'=>'span12',
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Email / Số điện thoại'),
	)); ?>
	</div>

	<div class="row-fluid">
	<?php echo $form->passwordFieldRow(	$model,'password', array(
		'class'=>'span12',
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu'),
	)); ?>
	</div>

	<div class="row-fluid">
		<div class="span5">
			<button type="submit" name="btnSignIn" class="btn btn-primary"><?= Yii::t('view', 'Đăng nhập'); ?></button>
		</div>
		<div class="span7 remember">
		<?= $this->widget(TbActiveForm::INPUT_HORIZONTAL, array(
			'type'=>TbInput::TYPE_CHECKBOX,
			'form'=>$form,
			'model'=>$model,
			'attribute'=>'remember',
		), true); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

	<a href="#" style="margin-bottom:0.5em;"><?= Yii::t('view', 'Không truy cập được tài khoản?'); ?></a>

</div></div>
