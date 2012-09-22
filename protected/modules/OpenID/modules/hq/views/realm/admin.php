<?php
/* @var $this RealmController */
/* @var $model OpenIDRealm */

$this->breadcrumbs=array(
	'OpenID Realms'=>array('admin'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create OpenIDRealm', 'url'=>array('create')),
);

$this->pageHeader = 'Manage OpenID Realms';
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'open-idrealm-grid',
	'type'=>array('striped', 'condensed'),
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'template'=>"{items}\n{summary}\n{pager}",
	'columns'=>array(
		array(
        	'name'=>'id',
        	'htmlOptions'=>array('style'=>'width:50px; text-align:right;'),
		),
		'realm',
		'type',
		array(
        	'name'=>'enable',
        	'htmlOptions'=>array('style'=>'width:50px; text-align:right;'),
		),
		array(
        	'name'=>'last_modified_time',
        	'htmlOptions'=>array('style'=>'text-align:right;'),
		),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
        	'htmlOptions'=>array('style'=>'width:60px; text-align:right;'),
		),
	),
)); ?>
