 <?php
  $secretQuestions = array_merge(array(0=>'-- không sử dụng câu hỏi bảo mật --'),$secretQuestions);
  $secretQuestions = array_merge($secretQuestions,array(1=>'-- Viết câu hỏi khác --'));
  $style = ($is_validate_create_question == true)?'display:none':'';
  
	if(!empty($accModel->auth->secret_answer)){
		$createQuestionFormModel->secret_answer  = '******';
		if(in_array($accModel->auth->secret_question,$secretQuestions)){
			$createQuestionFormModel->secret_question = $accModel->auth->secret_question;	
		}else{
			$createQuestionFormModel->secret_question = 1;
			$createQuestionFormModel->another_question = $accModel->auth->secret_question;
		}
	}
 ?>
  <?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'create-question-form',
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
<?php 	if(empty($accModel->auth->secret_answer)): 	?>
<legend><?= Yii::t('view', 'Tạo câu hỏi bảo mật'); ?></legend>
<?php else: ?>
<legend><?= Yii::t('view', 'Sửa câu hỏi bảo mật'); ?></legend>
<?php endif; ?>

	<?php echo $form->passwordFieldRow($createQuestionFormModel,'password', array(
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu của bạn'),)); ?>	
	
	<?php echo $form->dropDownListRow($createQuestionFormModel, 'secret_question', $secretQuestions,array(
		'class'=>'select-question',
	)); ?>
	<div class="controls another-question" id="another-question">
			<?= $form->TextField($createQuestionFormModel, 'another_question',array(
			'placeholder'=>Yii::t('view', 'Câu hỏi của bạn'),
			'class'=>'another-question-input',
			 )); ?>	
			<?php echo $form->error($createQuestionFormModel,'another_question'); ?>
		</div>	
	<?php echo $form->textFieldRow($createQuestionFormModel,'secret_answer', array(
			'maxlength'=>255,
			'placeholder'=>Yii::t('view', 'Trả lời câu hỏi bảo mật'), 
			)); ?>	
    
    <div class="controls">
      <button type="submit" class="btn btn-primary"><?= Yii::t('view','Cập nhật'); ?></button>
    </div>
<?php $this->endWidget(); ?>   

<script language="JavaScript">
	<!--
	jQuery(function($) {

		$(document).ready(function() {
			var select_val =  $('#CreateSecretQuestionForm_secret_question option:selected').val();	
				if(select_val == 1){
					$('.another-question').show();
				}
				if(select_val == 0){
					$('#CreateSecretQuestionForm_secret_answer').attr('disabled','disabled');
				}else{
					$('#CreateSecretQuestionForm_secret_answer').removeAttr('disabled');	
				}
			});

		$('#CreateSecretQuestionForm_secret_question').change(function(){
			var select_val =  $('#CreateSecretQuestionForm_secret_question option:selected').val();	
				if(select_val == 1){
					$('.another-question').show();
				}else $('.another-question').hide(); 
				if(select_val == 0){
					$('#CreateSecretQuestionForm_secret_answer').attr('disabled','disabled');
				}else{
					$('#CreateSecretQuestionForm_secret_answer').removeAttr('disabled');	
				}
			
			
		});
		
		$('#CreateSecretQuestionForm_secret_answer').click(function(e) {
        	if($('#CreateSecretQuestionForm_secret_answer').val() =='******'){
				$('#CreateSecretQuestionForm_secret_answer').val('');	
			}
		});	
		$('#create_question_label').click(function(e) {
        	$('#create-question-form').toggle('fast');
        	return false;
		});			
	});
		
	//-->

</script> 
