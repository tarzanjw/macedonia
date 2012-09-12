<?php
/* @var $this SignUpController */
/* @var $acc Acc */

//$this->breadcrumbs=array(
//	'Sign Up',
//);
$this->pageHeader = 'Tạo mới tài khoản Vật Giá';
?>

<?php $this->beginContent('//layouts/main'); ?>
<div class="row signup">
	<div class="span7"><?php include '_introduction.php'; ?></div>
	<div class="span5"><?php echo $content; ?></div>
</div><!-- content -->
<?php $this->endContent(); ?>