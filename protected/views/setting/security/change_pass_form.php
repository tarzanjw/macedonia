<style>
	/*.controls{
		position: relative;
	}
	span.help-inline{
		font-size: 12px;
		position: absolute;
		width: 238px;
		top: 31px;
		left: -5px;
	} */
</style>
<?php
	/** @var ChangePassForm $modelPass */
	$modelPass = $changePassFormModel;
	$style = ($is_validate_pass == true)?'display:none':'';
?>
<?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'changePass-form',
	'type'=>'horizontal',
//	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array(
		'class'=>'well form-horizontal form-change-pass span12',
		'style'=>$style,
	),
)); ?>
	<legend><?= Yii::t('view', 'Đổi mật khẩu'); ?></legend>
	
	<div id="divOldPassword" class="controls-row control-group">
		<div class="control-label control-label-change-pass">
			<label class="radio">
			<?php if(!empty($accModel->auth->secret_question)):?> 
		 	<?= $form->radioButton($modelPass, 'verifyMethod', array(
		 		'value'=>ChangePassForm::VERIFY_METHOD__PASSWORD,
		 		'id'=>'ChangePassForm_verifyMethod_'.ChangePassForm::VERIFY_METHOD__PASSWORD,
		 		'checked'=>'checked',
		 		'class'=>'change-pass-radio',
		 	)); ?>
		 	<?php
		 		endif;
		 	?>
		 	<?= $modelPass->getAttributeLabel('oldPassword'); ?>
			</label> 
		 	
		</div>
		<div class="controls">
			<?= $form->passwordField($modelPass, 'oldPassword',array(
			'placeholder'=>Yii::t('view', 'Mật khẩu hiện tại'), )); ?>	
			<?php echo $form->error($modelPass,'oldPassword'); ?>
		</div>
		
	</div>
	
	<?php if(!empty($accModel->auth->secret_question)):?> 
	<label class="label_or"><?= Yii::t('view', 'Hoặc'); ?></label>
	<div id="divQuestion" class="controls-row control-group control-label-change-pass control-disable">
		<div class="control-label">
			<label class="radio">
		 	<?= $form->radioButton($modelPass, 'verifyMethod', array(
		 		'value'=>ChangePassForm::VERIFY_METHOD__QUESTION,
		 		'id'=>'ChangePassForm_verifyMethod_'.ChangePassForm::VERIFY_METHOD__QUESTION,
		 		'class'=>'change-pass-radio',
		 	)); ?>
		 	<?= $modelPass->getAttributeLabel('secretQuestion'); ?>
			</label>
		 	
		</div>
		<div class="controls">
			<?= $form->textField($modelPass, 'secretQuestion',array(
					'disabled'=>'disabled',
					'placeholder'=>Yii::t('view', 'Câu hỏi bảo mật'),
			)); ?>
			<?php echo $form->error($modelPass,'secretQuestion'); ?>
		</div>
	</div>
    <?php endif; ?>
	
		<?php echo $form->passwordFieldRow($modelPass,'newPassword', array(
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Mật khẩu mới'),)); ?>	
	
	<?php echo $form->passwordFieldRow($modelPass,'confirmed_password', array(
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Xác nhận mật khẩu mới'), )); ?>	
  
    <div class="controls">
      <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
    </div>

<?php $this->endWidget(); ?>

<script language="JavaScript">
	<!--
	jQuery(function($) {

		$(document).ready(function() {
			if($('#ChangePassForm_verifyMethod_secretQuestion').attr('checked')=='checked') { 
				$('#ChangePassForm_verifyMethod_secretQuestion').trigger('click');
				return false;
			}
		});
		$('#changePass-form input[type=radio]').click(function(){
		
			if(this.value == 'oldPassword'){
				$('#ChangePassForm_secretQuestion').attr('disabled','disabled');
				$('#ChangePassForm_oldPassword').removeAttr('disabled');
				$('#divOldPassword').removeClass('control-disable');
				$('#divQuestion').addClass('control-disable');
				
			}else{
				$('#ChangePassForm_oldPassword').attr('disabled','disabled');
				$('#ChangePassForm_secretQuestion').removeAttr('disabled');
				$('#divQuestion').removeClass('control-disable');
				$('#divOldPassword').addClass('control-disable')	
			}
			$('#ChangePassForm_'+this.value).focus();
		});
		
		$('#change_pass_label').click(function(e) {
        	$('#changePass-form').toggle('fast');
		});
	});
	    /*jQuery(function($) {
					$('#ChangePassForm_secretQuestion').attr('disabled','disabled');
				});	
				
		function disableChoice(method){
			if(method == 'OldPasswd'){
				jQuery(function($) {
					$('#DivOldPassWord').addClass('control-disable');
					$('#DivQuestionSecurity').removeClass('control-disable');
					$('#ChangePassForm_oldPassword').attr('disabled','disabled');
					$('#ChangePassForm_secretQuestion').removeAttr('disabled');
				});
			}else{
				jQuery(function($) {
					$('#DivQuestionSecurity').addClass('control-disable');
					$('#DivOldPassWord').removeClass('control-disable');
					$('#ChangePassForm_secretQuestion').attr('disabled','disabled');
					$('#ChangePassForm_oldPassword').removeAttr('disabled');
				});	
			}
		} */
	//-->

</script>

