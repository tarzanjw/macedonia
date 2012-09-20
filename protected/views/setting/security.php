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
<div class="btn-change-pass page-header row-fluid">
	<label class="change_pass_label" id="change_pass_label"><?= Yii::t('view', 'Đổi mật khẩu'); ?></label>
	<div class="span11 row-fluid" style="min-height:0">
		<?php include 'security/change_pass_form.php'; ?>
	</div>
</div>

<div class="btn-change-pass page-header row-fluid">
	<?php 	if(empty($accModel->auth->secret_answer)): 	?>
		Bạn chưa có câu hỏi bảo mật.
		<label class="change_pass_label" id="create_question_label"><?= Yii::t('view', 'Tạo câu hỏi bảo mật'); ?></label>
	<?php else: ?>
		<label class="change_pass_label" id="create_question_label"><?= Yii::t('view', 'Sửa câu hỏi bảo mật'); ?></label>
	<?php endif; ?>
			
		<div class="span11 row-fluid" style="min-height:0">
			<?php include 'security/update_question_form.php'; ?>
		</div>	

</div>
 <div class="btn-change-pass page-header row-fluid">
	<label class="change_pass_label" id="change_phone_label"><?= Yii::t('view', 'Đổi số điện thoại'); ?></label>
	<div class="span11 row-fluid" style="min-height:0">
		<?php include 'security/change_phone_form.php'; ?>
	</div>
</div>
 

