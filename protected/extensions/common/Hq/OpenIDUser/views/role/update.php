<?php
$this->breadcrumbs=array(
	'Open Id Users'=>array('admin'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'Create OpenIdUser', 'url'=>array('create')),
	array('label'=>'View OpenIdUser', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage OpenIdUser', 'url'=>array('admin')),
);

$this->pageHeader = "Update OpenIdUser <em>{$model->email}</em>";
?>

<h1></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>