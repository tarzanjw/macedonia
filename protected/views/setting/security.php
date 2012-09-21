<?php
/* @var $this SettingController */
?>
<p class="lead"><?= Yii::t('view', 'Quản lý thông tin bảo mật'); ?></p>
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
<div class="btn-change-pass page-header row-fluid">
	<a class="btn change_pass_label" id="change_pass_label"><i class="icon-pencil"></i> <?= Yii::t('view', 'Đổi mật khẩu'); ?></a>
	<div class="span11 row-fluid" style="min-height:0">
		<?php include 'security/change_pass_form.php'; ?>
	</div>
</div>

<div class="btn-change-pass page-header row-fluid">
	<?php 	if(empty($accModel->auth->secret_answer)): 	?>
		<label class="change_pass_label" id="create_question_label"><?= Yii::t('view', 'Tạo câu hỏi bảo mật'); ?></label>
	<?php else: ?>
	<label style="float:left">
	<?php echo Yii::t('view','Câu hỏi bảo mật').': <b>'.$accModel->auth->secret_question.'?</b>';?>
	</label>
		<a class="btn change-phone-label" href="#" id="create_question_label" title=""><i class="icon-pencil"></i> <?= Yii::t('view', 'Đổi câu hỏi bảo mật'); ?></a>
	<?php endif; ?>
			
		<div class="span11 row-fluid" style="min-height:0">
			<?php include 'security/update_question_form.php'; ?>
		</div>	

</div>
 <div class="btn-change-pass page-header row-fluid">
	<label style="float:left">
	<?php echo Yii::t('view','Số điện thoại').': <b>'.$accModel->phone.'</b>';?>
	</label>
	<a class="btn change-phone-label" href="#" id="change_phone_label" title=""><i class=" icon-pencil"></i> <?= Yii::t('view', 'Đổi số điện thoại'); ?></a>
	<div class="span11 row-fluid" style="min-height:0">
		<?php
		 	if(!$show_verify_otp)
			 	include 'security/change_phone_form.php'; 
 				else include 'security/change_phone_verify_otp.php'; 
			 ?>
	</div>
</div>
 

