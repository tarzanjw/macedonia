 <?php if(empty($acc->auth->secret_answer)): ?>
<h2 style="margin-bottom: 10px;"><?= Yii::t('view', 'Bạn chưa có câu hỏi bảo mật'); ?></h2>
<?php else: ?>
<h2 style="margin-bottom: 10px;"><?= Yii::t('view', 'Câu hỏi bảo mật của bạn'); ?> : <i><?php echo $acc->auth->secret_question; ?>?</i></h2>
<?php endif; ?>	
  <?php /** @var TbActiveForm */$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'create-question-form',
	'type'=>'horizontal',
//	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array(
		'class'=>'well form-change-pass1 form-horizontal',
	),
)); ?>
<?php if(empty($acc->auth->secret_answer)):	?>
<legend><?= Yii::t('view', 'Tạo câu hỏi bảo mật'); ?></legend>
<?php else: ?>
<legend><?= Yii::t('view', 'Sửa câu hỏi bảo mật'); ?></legend>
<?php endif; ?>

	<?php echo $form->passwordFieldRow($formModel,'password', array(
		'maxlength'=>255,
		'placeholder'=>Yii::t('view', 'Mật khẩu của bạn'),)); ?>	
	
	<?php echo $form->dropDownListRow($formModel, 'secret_question', $formModel->secret_question_list,array(
		'class'=>'select-question',
	)); ?>
	<div class="another-question" style="display: none;">
		<?= $form->textFieldRow($formModel, 'another_question',array(
		'placeholder'=>Yii::t('view', 'Câu hỏi của bạn'),
		'class'=>'another-question-input',
		 )); ?>		
	 </div>	 
	<?php 
	$placeholderAnswer = Yii::t('view', 'Trả lời câu hỏi bảo mật');
	if(!empty($acc->auth->secret_answer)){
		$placeholderAnswer ='******';
	}
	echo $form->textFieldRow($formModel,'secret_answer', array(
			'maxlength'=>255,
			'placeholder'=>$placeholderAnswer, 
			)); ?>	
    
    <div class="controls">
      <button type="submit" class="btn btn-primary"><?= Yii::t('view','Cập nhật'); ?></button>
    </div>
<?php $this->endWidget(); ?>   

<script language="JavaScript">
	<!--
	jQuery(function($) {

		$(document).ready(function() {
			var select_val =  $('#SecretQuestionForm_secret_question option:selected').val();	
				if(select_val == 1){
					$('.another-question').show();
				}
				if(select_val == 0){
					$('#SecretQuestionForm_secret_answer').attr('disabled','disabled');
				}else{
					$('#SecretQuestionForm_secret_answer').removeAttr('disabled');	
				}
			});

		$('#SecretQuestionForm_secret_question').change(function(){
			var select_val =  $('#SecretQuestionForm_secret_question option:selected').val();	
				if(select_val == 1){
					$('.another-question').show();
				}else $('.another-question').hide(); 
				if(select_val == 0){
					$('#SecretQuestionForm_secret_answer').attr('disabled','disabled');
				}else{
					$('#SecretQuestionForm_secret_answer').removeAttr('disabled');
					$('#SecretQuestionForm_secret_answer').val('');		
				}
				
		});
		
		$('#SecretQuestionForm_secret_answer').click(function(e) {
        	if($('#SecretQuestionForm_secret_answer').val() =='******'){
				$('#SecretQuestionForm_secret_answer').val('');	
			}
		});	
		$('#create_question_label').click(function(e) {
        	$('#create-question-form').toggle('fast');
        	return false;
		});			
	});
		
	//-->

</script> 
