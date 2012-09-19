<?php
/* @var $this SettingController */
?>
<p class="lead"><?= Yii::t('view', 'Quản lý mật khẩu'); ?></p>
<div class="btn-change-pass page-header row-fluid">
<a href="#">Doi mat khau</a>
<div class="span11 row-fluid">
	<?php include 'security/change_pass_form.php'; ?>
</div>

</div>

<div class="btn-change-pass page-header">
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>Yii::t('view', 'Đổi câu hỏi bảo mật'),
    'type'=>'', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'small', // null, 'large', 'small' or 'mini'
)); ?>
</div>
<div class="btn-change-pass page-header">
<?php $this->widget('bootstrap.widgets.TbButton', array(
    'label'=>Yii::t('view', 'Đổi số điện thoại'),
    'type'=>'', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
    'size'=>'small', // null, 'large', 'small' or 'mini'
)); ?>
</div>

