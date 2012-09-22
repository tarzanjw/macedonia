<?php
/* @var $this RealmController */
/* @var $model OpenIDRealm */
/* @var $form TbActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'open-idrealm-form',
	'type'=>'horizontal',
	'htmlOptions'=>array('class'=>'well'),
	'enableAjaxValidation'=>false,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?= $form->textFieldRow($model, 'realm'); ?>

	<?= $form->radioButtonListInlineRow($model, 'type', array(
		OpenIDRealm::TYPE_ALLOW=>'Allow',
		OpenIDRealm::TYPE_DENY=>'Deny',
		OpenIDRealm::TYPE_DEPEND_ON_ACCOUNT=>'Depend on Account',
	)); ?>

	<?= $form->radioButtonListInlineRow($model, 'enable', array(
		'1'=>'Enable',
		'0'=>'Disable',
	)); ?>

	<div class="form-actions">
	    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'label'=>$model->isNewRecord ? 'Create':'Save')); ?>
	    <?php $this->widget('bootstrap.widgets.TbButton', array(
//	    	'buttonType'=>'submit',
//	    	'type'=>'primary',
	    	'label'=>'Cancel',
	    	'url'=>$model->isNewRecord ? array('admin'):array('view','id'=>$model->id),
		)); ?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->