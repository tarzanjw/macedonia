<?php
/* @var $this SettingController */
?>

<?php
$userModel = $this->getCurrentUser();
$items = array(
    array('label'=>Yii::t('view', 'Tài khoản'), 'icon'=>'user', 'url'=>array('/setting/index'),
    		'visible'=>false,
    ),
    array('label'=>Yii::t('view', 'Thông tin'), 'icon'=>'pencil', 'url'=>array('/setting/info')),
    array('label'=>Yii::t('view', 'Bảo mật'), 'icon'=>'lock', 'url'=>array('/setting/security')),
    array('label'=>Yii::t('view', 'Bảo Kim'), 'icon'=>'baokim', 'url'=>array('/setting/baokim')),
    array('label'=>Yii::t('view', 'Sản phẩm'), 'icon'=>'book', 'url'=>array('/setting/products')),
    array('label'=>Yii::t('view', 'Kích hoạt'), 'icon'=>'icon-check', 'url'=>array('/setting/activate'),
    	'visible'=>$userModel->status != 'NORMAL',
    ),
);
    
 $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'pills',
    'stacked'=>true,
    'items'=>$items,
)); ?>
