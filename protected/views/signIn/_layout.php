<?php
/* @var $this SignUpController */
/* @var $acc Acc */

//$this->breadcrumbs=array(
//	'Sign Up',
//);
$this->pageHeader = 'Tạo mới tài khoản Vật Giá';
$this->setPageTitle(Yii::t('view', 'Đăng nhập').' - '.Yii::app()->name);
?>

<?php $this->beginContent('//layouts/main'); ?>
<div class="row signin">
	<div class="span7 product"><?php include '_product.php'; ?></div>
	<div class="span4 pull-right signin-box"><?php echo $content; ?></div>
</div><!-- content -->
<?php $this->endContent(); ?>