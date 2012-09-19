<?php /** @var BootActiveForm */$form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
	'id'=>'open-id-user-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('class'=>'well'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model, 'email', array('class'=>'span8')); ?>
	<?php echo $form->textFieldRow($model, 'name', array('class'=>'span8')); ?>

	<?php echo $form->checkBoxListRow($model, 'roles',  array_combine($this->module->roles,$this->module->roles), array('multiple'=>"multiple")); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.BootButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok', 'label'=>$model->isNewRecord ? 'Create' : 'Save')); ?>
	</div>

<?php $this->endWidget(); ?>
