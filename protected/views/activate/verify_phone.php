<h2><?php
		echo Yii::t('view','Kích hoạt tài khoản');
	?></h2>
	<h4><?php
			echo Yii::t('view', 'Nhập mã kích hoạt để xác minh số điện thoại');
		?></h4> 
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
 ?>
<form action="" method="post">
	<input type="text" class="input-sms-code" name="sms_code"/>
								
	<button class="btn btn-primary" type="submit"><i class="icon-ok"></i><?php
									echo Yii::t('view', 'Kích hoạt');
								?></button>
</form>
