<?php
$this->breadcrumbs=array(
    'OpenID Users'=>array('admin'),
    'Manage',
);

$this->menu=array(
    array('label'=>'List OpenIDUser','url'=>array('index')),
    array('label'=>'Create OpenIDUser','url'=>array('create')),
);

$this->pageHeader = 'Manage OpenID Users';
?>

<form method="POST">
<?php $this->widget('bootstrap.widgets.TbGridView',array(
    'id'=>'open-iduser-grid',
    'dataProvider'=>$model->search(),
    'ajaxUpdate'=>false,
    'filter'=>$model,
    'columns'=>array(
        'id',
        'email',
        array(
        	'name'=>'name',
        	'type'=>'raw',
        	'value'=>function($e) {
            	if (empty($e->avatar)) return CHtml::tag('span', array(
                	'style'=>'margin-left:20px',
            	), '&nbsp;'.CHtml::encode($e->name), true);

            	return CHtml::image($e->avatar, $e->name, array('style'=>'width:20px')).' '.CHtml::encode($e->name);
        	},
        ),
        array(
        	'name'=>'enable',
        	'filter'=>CHtml::activeDropDownList($model, 'enable', array(
            	''=>'All',
            	0=>'Disabled',
            	1=>'Enabled',
        	)),
        	'type'=>'raw',
        	'htmlOptions'=>array(
            	'style'=>'text-align:right;width:100px;'
        	),
        	'value'=>function($e) {
                if ($e->enable)
                	return Yii::app()->controller->widget('bootstrap.widgets.TbButton', array(
                		'buttonType'=>'submit',
                		'label'=>'Disable',
                		'icon'=>'thumbs-down',
                		'htmlOptions'=>array(
                        	'name'=>'btnDisable',
                        	'value'=>$e->email,
                		),
                	), true);

                return Yii::app()->controller->widget('bootstrap.widgets.TbButton', array(
                		'buttonType'=>'submit',
                	'label'=>'Enable',
                	'icon'=>'thumbs-up',
                	'type'=>'info',
                	'htmlOptions'=>array(
                        'name'=>'btnEnable',
                        'value'=>$e->email,
                	),
                ), true);
			}
        ),
        array(
        	'name'=>'created_time',
        	'htmlOptions'=>array(
            	'style'=>'text-align:right;'
        	),
        ),
        /*array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'buttons'=>array(
            	'disable'=>array(
            		'icon'=>'ban-circle',
            		'label'=>'Disable',
            	),
            ),

            'template'=>'{disable} {delete}',
            'template'=>'{delete}',
        ),*/
    ),
)); ?>
</form>