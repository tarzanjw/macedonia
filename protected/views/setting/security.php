<?php
/* @var $this SettingController */ ?>

<?php $this->widget('bootstrap.widgets.TbMenu', array(
    'type'=>'tabs', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>array(
        array('label'=>'General', 'url'=>array('', 'tab'=>'general'), 'active'=>$tab=='general'),
        array('label'=>'Password', 'url'=>array('', 'tab'=>'password'), 'active'=>$tab=='password'),
        array('label'=>'Phone', 'url'=>array('', 'tab'=>'phone'), 'active'=>$tab=='phone'),
        array('label'=>'Secret Question', 'url'=>array('', 'tab'=>'question'), 'active'=>$tab=='question'),
    ),
)); ?>

<?php
/* @var $this SettingController */
return;
?>
<?php $this->widget('bootstrap.widgets.TbTabs', array(
	'id'=>'security-tab',
    'type'=>'tabs', // 'tabs' or 'pills'
    'htmlOptions'=>array('class'=>'row-fluid'),
    'tabs'=>array(
        array(
        	'label'=>Yii::t('view', 'Tổng quan'), 
        	'content'=>$this->renderPartial('security/_general', array('acc'=>$accModel), true), 
        	'id'=>'general',
        	'active'=>$tab=='general',
        ),
        array(
        	'label'=>Yii::t('view', 'Mật khẩu'), 
        	'content'=>$this->renderPartial('security/_password', array(
        		'changePassFormModel'=>$changePassFormModel,
        		'is_validate_pass'=>$is_validate_pass,
        	), true), 
        	'id'=>'password',
        	'active'=>$tab=='password',
        ),
        array(
        	'label'=>Yii::t('view', 'Điện thoại di động'), 
        	'content'=>$this->renderPartial('security/_phone', array(
        	'is_validate_phone'=>$is_validate_phone,
        	'changePhoneFormModel'=>$changePhoneFormModel,
        	'captchaModel'=>$captchaModel,
        	), true), 
        	'id'=>'phone',
        	'active'=>$tab=='phone',
        ),
        array(
        	'label'=>Yii::t('view', 'Câu hỏi bảo mật'), 
        	'content'=>$this->renderPartial('security/_question', array(
        		'secretQuestions' => $secretQuestions,
        		'is_validate_create_question' => $is_validate_create_question,
        		'accModel'=>$accModel,
        		'createQuestionFormModel' => $createQuestionFormModel,
        	), true), 
        	'id'=>'question',
        	'active'=>$tab=='question',
        ),
    ),
)); ?>