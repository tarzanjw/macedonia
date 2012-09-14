<?php
/* @var $this SignUpController */
/* @var $acc Acc */

//$this->breadcrumbs=array(
//	'Sign Up',
//);
$this->setPageTitle(Yii::t('view', 'Đăng nhập').' - '.Yii::app()->name);
?>

<?php $this->beginContent('//layouts/main', array(
	'customButtons'=>$this->renderPartial('_buttons', array(), true),
)); ?>
<div class="row signin">
	<div class="span7 product"><?php include '_product.php'; ?></div>
	<div class="span4 pull-right signin-box"><?php echo $content; ?></div>
</div><!-- content -->
<?php $this->endContent(); ?>