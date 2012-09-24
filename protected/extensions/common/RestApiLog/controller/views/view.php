<?php
$this->breadcrumbs=array(
	$this->logModelClass=>array('admin'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Manage '.$this->logModelClass, 'url'=>array('admin')),
	array('label'=>'Delete '.$this->logModelClass, 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View <?php echo $this->logModelClass; ?> #<?php echo $model->id; ?></h1>

<?php 
	parse_str(urldecode($model->post_data), $postData);
?>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'request_method',
		'request_url',
		'src_ip',
		'dst_ip',
		array(
			'name'=>'request_headers',
			'type'=>'raw',
			'value'=>'<pre>'.$model->request_headers."</pre>",
		),
		array(
			'name'=>'response_headers',
			'type'=>'raw',
			'value'=>'<pre>'.$model->response_headers."</pre>",
		),
		'request_time',
		'duration',
		array(
			'name'=>'post_data',
			'type'=>'raw',
			'value'=>'<pre>'.print_r($postData, true)."</pre>",
		),
		'response_data',
	),
)); ?>
