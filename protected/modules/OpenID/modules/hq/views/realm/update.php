<?php
/* @var $this RealmController */
/* @var $model OpenIDRealm */

$this->breadcrumbs=array(
	'Open Idrealms'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List OpenIDRealm', 'url'=>array('index')),
	array('label'=>'Create OpenIDRealm', 'url'=>array('create')),
	array('label'=>'View OpenIDRealm', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OpenIDRealm', 'url'=>array('admin')),
);
?>

<h1>Update OpenIDRealm <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>