<?php
/* @var $this SettingController */

?>

<?php 
$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
 ?>
<h2 class="redtext"><?= Yii::t('view', 'Trang này đang được xây dựng. Mời bạn quay lại sau.'); ?></h2>