<?php
$this->breadcrumbs=array(
	'Open Id Users'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create new', 'url'=>array('create')),
);

$this->pageHeader = 'Manage Open Id Users';

?>

<?php $this->widget('bootstrap.widgets.BootGridView', array(
	'type'=>'striped',
	'id'=>'open-id-user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
        	'name'=>'email',
        	'type'=>'raw',
        	'value'=>'CHtml::link($data->email, array("view", "id"=>$data->id))',
		),
		array(
        	'name'=>'name',
        	'type'=>'raw',
        	'value'=>'CHtml::link($data->name, array("view", "id"=>$data->id))',
		),
		array(
			'name'=>'roles',
			'value'=>'implode(", ", $data->roles)',
			'type'=>'raw',
		),
		array(
			'class'=>'bootstrap.widgets.BootButtonColumn',
		),
	),
)); ?>
