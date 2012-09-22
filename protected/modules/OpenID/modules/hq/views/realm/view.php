<?php
/* @var $this RealmController */
/* @var $model OpenIDRealm */

$this->breadcrumbs=array(
	'OpenID Realms'=>array('admin'),
	$model->realm,
);

$this->menu=array(
	array('label'=>'Create OpenIDRealm', 'url'=>array('create')),
	array('label'=>'Update OpenIDRealm', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete OpenIDRealm', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OpenIDRealm', 'url'=>array('admin')),
);

$this->pageHeader = 'OpenIDRealm <em>'.$model->realm.'</em>';
?>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'realm',
		'type',
		'enable',
		'created_time',
		'last_modified_time',
		array(
        	'label'=>'',
        	'type'=>'raw',
        	'value'=>$this->widget('bootstrap.widgets.TbButton', array(
            	'label'=>'Update',
            	'type'=>'info',
            	'size'=>'small',
            	'url'=>$this->createUrl('update',array('id'=>$model->id)),
        	), true),
		),
	),
)); ?>
