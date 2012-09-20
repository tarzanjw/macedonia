<?php
/* @var $this SettingController */
?>
<p class="lead"><?= Yii::t('view', 'Quản lý mật khẩu'); ?></p>
<div class="btn-change-pass page-header row-fluid">
	<label class="change_pass_label" id="change_pass_label"><?= Yii::t('view', 'Đổi mật khẩu'); ?></label>
	<?php $this->widget('bootstrap.widgets.TbAlert', array(
		'block'=>true, // display a larger alert block?
		'fade'=>true, // use transitions?
		'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
		'alerts'=>array( // configurations per alert type
		    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
		),
	));?>	
	<div class="span11 row-fluid" style="min-height:0">
		<?php include 'security/change_pass_form.php'; ?>
	</div>
</div>

<div class="btn-change-pass page-header row-fluid">
	<?php
		if(empty($accModel->auth->secret_answer)):
	?>
		Bạn chưa có câu hỏi bảo mật.
		<label class="change_pass_label" id="create_question_label"><?= Yii::t('view', 'Tạo câu hỏi bảo mật'); ?></label>
		
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>true, // display a larger alert block?
			'fade'=>true, // use transitions?
			'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			'alerts'=>array( // configurations per alert type
			    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			),
		));?>
			
		<div class="span11 row-fluid" style="min-height:0">
			<?php include 'security/create_question_form.php'; ?>
		</div>	
	<?php
		else:
	?>
	<?php
		endif;
	?>

</div>


<div class="btn-change-pass page-header">
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>Yii::t('view', 'Đổi số điện thoại'),
    'type'=>'', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'small', // null, 'large', 'small' or 'mini'
)); ?>
</div>

