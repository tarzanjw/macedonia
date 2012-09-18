   
	<label class="bluetext"><?php
			echo Yii::t('view', 'Nhập mã kích hoạt để xác minh số điện thoại');
		?></label>  

<form action="" method="post" class="verify_phone">
	<input type="text" class="input-sms-code" name="sms_code"/>
								
	<button class="btn btn-primary" type="submit"><i class="icon-ok"></i><?php
									echo Yii::t('view', 'Xác minh');
								?></button>  
</form>
<lable class="resend_sms_label" id="resend_sms_label"> Gửi lại mã kích hoạt </lable>
		<?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success-sms'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
        'htmlOptions'=>array('class'=>'success-sms')
    ));?>
    <?php $this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'error-sms'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
        'htmlOptions'=>array('class'=>'error-sms')
    ));?>
<script language="JavaScript">	

jQuery(function($) {
		$('#resend_sms_label').click(function(e) {
        	$('#kind').val('sms');
        	$('#captcha-form').fadeOut(200);
        	$('#captcha-form').hide();
        	$('#captcha-form').removeClass('email');
        	$('#captcha-form').fadeIn(200);
        	});
		});
</script>
