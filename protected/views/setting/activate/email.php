
	<label class="bluetext"><?php
			echo Yii::t('view', 'Nhập mã kích hoạt để xác minh số email');
		?></label>  
 
<form action="" method="post" class="verify_phone">
	<input type="text" class="input-sms-code" name="email_code"/>
								
	<button class="btn btn-primary" type="submit"><i class="icon-ok"></i><?php
									echo Yii::t('view', 'Xác minh');
								?></button>  
</form>
<lable class="resend_sms_label" id="resend_email_label"> Gửi lại mã kích hoạt </lable>
 	<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
        'success-email'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
    ),
     'htmlOptions'=>array('class'=>'success-email')
));?>
<?php $this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
        'error-email'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
    ),
    'htmlOptions'=>array('class'=>'error-email')
    ));?>
<script language="JavaScript">	

jQuery(function($) {
		$('#resend_email_label').click(function(e) {
        	$('#kind').val('email');
        	$('#captcha-form').hide();
        	$('#captcha-form').addClass('email');
        	$('#captcha-form').fadeIn(200);
        	});
		});
</script>
<style>
.email{
	margin-left:358px;
}
</style>
