<?php
$this->breadcrumbs=array(
	'Open Id Users'=>array('admin'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Create OpenIdUser', 'url'=>array('create')),
	array('label'=>'Update OpenIdUser', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OpenIdUser', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OpenIdUser', 'url'=>array('admin')),
);

$this->pageHeader = "View OpenIdUser <em>{$model->email}</em>";
?>

<?php $this->widget('bootstrap.widgets.BootDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'email',
		'name',
		array(
			'label'=>'Roles',
			'type'=>'raw',
			'value'=>implode(', ', $model->roles),
		),
	),
)); ?>
