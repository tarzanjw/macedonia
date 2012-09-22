<?php
$this->breadcrumbs=array(
	'Open Id Users'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage OpenIdUser', 'url'=>array('admin')),
);

$this->pageHeader = 'Create OpenIdUser';
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>