<?php
/* @var $this SignUpController */
/* @var $acc Acc */
?>

<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'acc-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
    	'class'=>'well',
    ),
)); ?>

    <!--<p class="help-block">Fields with  are required.</p>-->

    <?php echo $form->errorSummary($acc); ?>
    <?php echo $form->errorSummary($f); ?>

    <label>Họ và tên</label>
    <div class="row-fluid">
    	<div class="span6">
	    <?= $form->textField($acc, 'first_name', array(
    		'placeholder'=>'Họ đệm',
    		'class'=>'span12',
	    )); ?>
	    </div>
	    <div class="span6">
	    <?= $form->textField($acc, 'last_name', array(
    		'placeholder'=>'Tên',
    		'class'=>'span12',
	    )); ?>
	    </div>
    </div>

    <label>Email</label>
    <div class="row-fluid">
    <?php echo $form->textField($acc,'email',
    	array(
    		'class'=>'span5',
    		'maxlength'=>255,
    		'placeholder'=>'Email của bạn',
    		'class'=>'span12'
    )); ?>
    </div>

    <label>Điện thoại di động</label>
    <div class="row-fluid">
    <?php echo $form->textField($acc,'phone',array(
    	'class'=>'span5',
    	'maxlength'=>255,
    	'placeholder'=>'Số điện thoại di động của bạn',
    	'class'=>'span12',
    )); ?>
	</div>

    <?php #echo $form->textAreaRow($acc,'avatar',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

    <label>Giới tính</label>
    <div class="row-fluid">
    <?php echo $form->dropDownList($acc,'gender',
    	array(
    		''=>'Tôi là ...',
    		Acc::GENDER_MALE=>'Nam',
    		Acc::GENDER_FEMALE=>'Nữ',
    		Acc::GENDER_OTHER=>'Khác',
    	),
    	array('class'=>'span12','maxlength'=>6));
    ?>
    </div>

    <label>Ngày sinh</label>
    <?php echo $form->textField($acc,'dob',array(
    	'class'=>'span5 datepicker',
    	'placeholder'=>'Tôi sinh ngày'
    	));
    ?>

    <label>Địa chỉ</label>
    <div class="row-fluid">
    <?php echo $form->textArea($acc,'address',array(
    	'rows'=>2,
    	'cols'=>50,
    	'class'=>'span12',
    	'placeholder'=>'Địa chỉ nơi bạn sinh sống',
    )); ?>
    </div>

    <label>Thành phố</label>
    <div class="row-fluid">
    <?php echo $form->dropDownList($acc,'city_id', CHtml::listData(City::model()->findAll(), 'id', 'name'), array(
    	'class'=>'span5',
    	'placeholder'=>'Thành phố nơi bạn sinh sống',
    )); ?>
    </div>

    <label class="checkbox">
    	<?= $form->checkBox($f, 'agreeToS'); ?>
        Tôi đồng ý với <a href="#" target="_blank">Điều khoản dịch vụ</a> và <a href="#" target="_blank">Chính sách bảo mật</a> của Vật Giá.
  	</label>

    <div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'htmlOptions'=>array(
            	'class'=>'pull-right',
            ),
            'label'=>'Bước tiếp theo',
        )); ?>
    </div>

<?php $this->endWidget(); ?>
