	<p class="lead"><?= Yii::t('view', 'Thay đổi thông tin cá nhân'); ?></p> 
	
	<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'edit-info-form',
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
	<legend><?= Yii::t('view', 'Thay đổi thông tin cá nhân'); ?></legend>
	
	
	<div class="control-group row-fluid">
		<label class="control-label span2"><?= Yii::t('view', 'Họ và tên'); ?> <span class="required">*</span></label>
			<?= $form->textField($infoModel, 'first_name', array(
				'placeholder'=>Yii::t('view', 'Họ Đệm'),
				'class'=>'span4',
			)); ?>
			<?= $form->textField($infoModel, 'last_name', array(
				'placeholder'=>Yii::t('view', 'Tên'),
				'class'=>'span2 input-last-name',
			)); ?>
			<?= $form->hiddenField($infoModel, 'name', array(
				'class'=>'span5',
			)); ?>

			<?= $form->error($infoModel, 'name'); ?>
	</div>
	<div class="control-group row-fluid">
		<label class="control-label span2"><?= Yii::t('view', 'Giới tính'); ?> <span class="required">*</span></label>
		<?php echo $form->dropDownList($infoModel,'gender',
			array(
				''=>'Tôi là ...',
				Acc::GENDER_MALE=>'Nam',
				Acc::GENDER_FEMALE=>'Nữ',
				Acc::GENDER_OTHER=>'Khác',
			),
			array('class'=>'span6'));
		?>
		<?= $form->error($infoModel, 'gender'); ?>
	</div>

    <div class="control-group row-fluid">
		<label class="control-label span2"><?= Yii::t('view', 'Ngày sinh'); ?> <span class="required">*</span></label>
        <?php echo $form->textField($infoModel,'dobD',array(
			'class'=>'span2',
			'style'=>'text-align: right;',
			'placeholder'=>Yii::t('view', 'Ngày')
			));
		?>
		<?php echo $form->textField($infoModel,'dobM',array(
			'class'=>'span2 input-birthday',
			'style'=>'text-align: right;',
			'placeholder'=>Yii::t('view', 'Tháng')
			));
		?>
		<?php echo $form->textField($infoModel,'dobY',array(
			'class'=>'span2 input-birthday',
			'style'=>'text-align: right;',
			'placeholder'=>Yii::t('view', 'Năm')
			));
		?>
		<?= $form->hiddenField($infoModel, 'dob', array(
				'class'=>'span5',
			)); ?>
		<?= $form->error($infoModel, 'dob'); ?>
	</div>
    <div class="control-group row-fluid">
    	<label class="control-label span2"><?= Yii::t('view', 'Địa chỉ'); ?> <span class="required">*</span></label>	
    	<?php echo $form->textArea($infoModel,'address',array(
		'rows'=>2,
		'cols'=>50,
		'class'=>'span6',
		'placeholder'=>Yii::t('view', 'Địa chỉ nơi bạn sinh sống'),
	)); ?>
	<?= $form->error($infoModel, 'address'); ?>
    </div>
	
    <div class="control-group row-fluid">
    	<label class="control-label span2"><?= Yii::t('view', 'Thành phố'); ?> <span class="required">*</span></label>	
    	<?php echo $form->dropDownList($infoModel,'city_id', array(''=>'Tôi sống ở ...')+CHtml::listData(City::model()->findAll(), 'id', 'name'), array(
		'class'=>'span6',
		'placeholder'=>Yii::t('view', 'Thành phố nơi bạn sinh sống'),
	)); ?>
	<?= $form->error($infoModel, 'city_id'); ?>
    </div>
  
    <div class="form-actions">
		<button type="submit" class="btn btn-primary"><?= Yii::t('view', 'Thay đổi'); ?></button>
		<a href="<?= $this->createUrl(''); ?>" class="btn"><?= Yii::t('view', 'Hủy bỏ'); ?></a>
	</div>

<?php $this->endWidget(); ?>
