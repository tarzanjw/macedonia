<?php
$this->breadcrumbs=array(
	$this->logModelClass=>array('admin'),
	'Manage',
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'http-conn-log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'class'=>'CLinkColumn',
			'header'=>'id',
			'labelExpression'=>'$data->id',
			'urlExpression'=>'Yii::app()->controller->createUrl("view",array("id"=>$data->id))',
			'htmlOptions'=>array(
				'style'=>'width:50px;text-align:right;',
			),
		),
		array(
			'name'=>'request_method',
			'htmlOptions'=>array(
				'style'=>'width:50px;',
			),
		),
		'request_url',
		array(
			'name'=>'src_ip',
			'htmlOptions'=>array(
				'style'=>'width:90px;',
			),
		),
		array(
			'name'=>'dst_ip',
			'htmlOptions'=>array(
				'style'=>'width:90px;',
			),
		),
		//array(
//			'name'=>'request_headers',
//			'type'=>'raw',
//			'value'=>'"<pre>".$data->request_headers."</pre>"',
//		),
//		array(
//			'name'=>'response_headers',
//			'type'=>'raw',
//			'value'=>'"<pre>".$data->response_headers."</pre>"',
//		),
		array(
			'name'=>'request_time',
			'htmlOptions'=>array(
				'style'=>'width:120px;',
			),
		),
		'duration',
		/*
		'post_data',
		'response_data',
		*/
	),
)); ?>
