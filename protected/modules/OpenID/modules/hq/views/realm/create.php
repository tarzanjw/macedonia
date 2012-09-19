<?php
/* @var $this RealmController */
/* @var $model OpenIDRealm */

$this->breadcrumbs=array(
	'OpenID Realms'=>array('admin'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage OpenIDRealm', 'url'=>array('admin')),
);

$this->pageHeader = 'Create OpenIDRealm';
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>