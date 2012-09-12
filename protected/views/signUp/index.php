<?php
/* @var $this SignUpController */
/* @var $model SignUpForm */

//$this->breadcrumbs=array(
//	'Sign Up',
//);

$this->pageHeader = Yii::t('view', 'Tạo mới tài khoản Vật Giá');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/signUp.css');
?>

<div class="row-fluid">
<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'acc-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'htmlOptions'=>array(
		'class'=>'well',
	),
)); ?>

	<!--<p class="help-block">Fields with  are required.</p>-->

	<?php #echo $form->errorSummary($model); ?>

	<label><?= Yii::t('view', 'Họ và tên'); ?> <span class="required">*</span></label>
	<div class="controls controls-rows">
		<?= $form->textField($model, 'first_name', array(
			'placeholder'=>Yii::t('view', 'Họ Đệm'),
			'class'=>'span7 pull-left',
		)); ?>
		<?= $form->textField($model, 'last_name', array(
			'placeholder'=>Yii::t('view', 'Tên'),
			'class'=>'span5 pull-right',
		)); ?>
		<?= $form->hiddenField($model, 'name', array(
			'class'=>'span5 pull-right',
		)); ?>

		<?= $form->error($model, 'name'); ?>
	</div>

	<?php echo $form->textFieldRow($model,'email', array(
			'class'=>'span12',
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Email của bạn'),
	)); ?>

	<?php echo $form->textFieldRow($model,'phone', array(
		'class'=>'span12',
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Số điện thoại di động của bạn'),
	)); ?>

	<?php echo $form->textFieldRow(	$model,'password', array(
		'class'=>'span12',
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu'),
	)); ?>

	<?php echo $form->textFieldRow($model,'confirmed_password', array(
		'class'=>'span12',
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Gõ lại mật khẩu'),
	)); ?>

	<?php #echo $form->textAreaRow($model,'avatar',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->dropDownListRow($model,'gender',
		array(
			''=>'Tôi là ...',
			Acc::GENDER_MALE=>'Nam',
			Acc::GENDER_FEMALE=>'Nữ',
			Acc::GENDER_OTHER=>'Khác',
		),
		array('class'=>'span12'));
	?>

	<?= $form->label($model, 'dob'); ?>
	<div class="controls controls-rows">
		<?php echo $form->textField($model,'dobD',array(
			'class'=>'span3',
			'style'=>'text-align: right;',
			'placeholder'=>Yii::t('view', 'Ngày')
			));
		?>
		<?php echo $form->textField($model,'dobM',array(
			'class'=>'span4',
			'style'=>'text-align: right;',
			'placeholder'=>Yii::t('view', 'Tháng')
			));
		?>
		<?php echo $form->textField($model,'dobY',array(
			'class'=>'span5 pull-right',
			'style'=>'text-align: right;',
			'placeholder'=>Yii::t('view', 'Năm')
			));
		?>
		<?= $form->error($model, 'dob'); ?>
	</div>

	<?php echo $form->textAreaRow($model,'address',array(
		'rows'=>2,
		'cols'=>50,
		'class'=>'span12',
		'placeholder'=>Yii::t('view', 'Địa chỉ nơi bạn sinh sống'),
	)); ?>

	<?php echo $form->dropDownListRow($model,'city_id', array(''=>'Tôi sống ở ...')+CHtml::listData(City::model()->findAll(), 'id', 'name'), array(
		'class'=>'span12',
		'placeholder'=>Yii::t('view', 'Thành phố nơi bạn sinh sống'),
	)); ?>

	<?php echo $form->label($model, 'captcha'); ?>
	<?php $this->widget('ext.common.recaptcha.EReCaptcha', array(
		'model'=>$model,
		'attribute'=>'captcha',
		'theme'=>'custom',
		'language'=>Yii::app()->language,
		'publicKey'=>'6Lc_YNYSAAAAAEEDPneMru1h_OQrrtQAcFrmBcOy'
	));
	?>
	<?php echo CHtml::error($model, 'captcha'); ?>

	<?= $form->checkBoxRow($model, 'agreeTermOfService'); ?>

	<div class="form-actions controls controls-row" style="padding-right: 0;">
		 <button type="submit" name="btnNextStep" class="btn btn-primary pull-right">Bước tiếp theo</button>
	</div>
<?php $this->endWidget(); ?>
</div>

<script language="JavaScript">
<!--
jQuery(function($) {
	var iName = $("#<?php $attr='name'; echo CHtml::getIdByName(CHtml::resolveName($model, $attr)); ?>");
	var iFirstName = $("#<?php $attr='first_name'; echo CHtml::getIdByName(CHtml::resolveName($model, $attr)); ?>");
	var iLastName = $("#<?php $attr='last_name'; echo CHtml::getIdByName(CHtml::resolveName($model, $attr)); ?>");

	onNamesChange = function() {
		iName.val(iFirstName.val() + ' ' + iLastName.val());
		iName.trigger('change');
	};

	onNamesBlur = function() {
		iName.trigger('blur');
	}

	iFirstName.change(onNamesChange);
	iLastName.change(onNamesChange);

	iFirstName.blur(onNamesBlur);
	iLastName.blur(onNamesBlur);
});
//-->
</script>