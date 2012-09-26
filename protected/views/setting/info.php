 <p class="lead"><?= Yii::t('view', 'Thông tin tài khoản'); ?> </p> 
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
 <?php
	switch ($infoModel->gender) {
			case Acc::GENDER_MALE:
				$gender = Yii::t('view', 'Nam'); break;
			case Acc::GENDER_FEMALE:
				$gender = Yii::t('view', 'Nữ'); break;
			case Acc::GENDER_OTHER:
				$gender = Yii::t('view', 'Khác'); break;
			default:
				null;
		}
 ?>
 <?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$infoModel,
	'attributes'=>array(
		'email',
		'first_name',
		'last_name',
		'birthday',
		'address',
		'gender'=>array(
			'label'=>Yii::t("view","Giới tính"),
			'type'=>'raw',
			'value'=>$gender,
		),
		'city_id'=>array(
			'label'=>Yii::t("view","Thành phố"),
			'type'=>'raw',
			'value'=>$accModel->city->name,
		),
		'city_id'=>array(
			'label'=>'',
			'type'=>'raw',
			'value'=>CHtml::link('<i class="icon-pencil"></i> '.Yii::t('view','Thay đổi'),$this->createUrl('',array('a'=>'edit')),array(
				'class'=>'btn btn-small',
			)),
		),
	),
)); ?>



