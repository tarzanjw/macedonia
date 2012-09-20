<?php
  class CreateSecretQuestionForm extends CFormModel
  {
  	public $_accModel;
  	public $password;
  	public $secret_question;
  	public $another_question;
  	public $secret_answer;
  	
  	function init()
		{
			$this->getAccModel();
		}
		
	function attributeLabels()
	{
		return array(
			'password'=>Yii::t('view', 'Mật khẩu').':',
			'secret_question'=>Yii::t('view', 'Câu hỏi bảo mật').':',
			'secret_answer'=>Yii::t('view', 'Trả lời câu hỏi bảo mật').':',
		);
	}
	
	public function getAccModel()
	{
		
		if (!isset($this->_accModel)) {
			$acc_id = Yii::app()->user->id;
			$this->_accModel = Acc::model()->with('auth')->findByPk($acc_id); 
			if(empty($this->_accModel))
				throw new CHttpException(403,'Not authorize.');
		}

		return $this->_accModel;
	}
	
	function checkPassword($field,$params)
	{
		if($this->accModel->auth->password !== SecurityHelper::hashPassword($this->password, $this->accModel->auth->password_salt)){
			$this->addError('password',$params['invalidMessage']);
			return;		
		}
	}
	
	function validateSecretAnswer($field,$params)
	{
		if($this->secret_question != '0' && empty($this->secret_answer)){
			$this->addError('secret_answer',$params['invalidMessage']);
			return;		
		}		
	}
	
	function validateAnotherQuestion($field,$params)
	{
		if($this->secret_question == '1' && empty($this->another_question)){
			$this->addError('another_question',$params['invalidMessage']);
			return;		
		}				
	}
	
	function clientValidateAnotherQuestion()
	{
		$secret_question = get_class($this).'_secret_question';
		$another_question = get_class($this).'_another_question';
		$invalidMessage=Yii::t('view', 'Bạn cần nhập câu hỏi của bạn.');

		$js = <<<JS
var secret_question = $('#{$secret_question}').val();
var another_question = $('#{$another_question}').val();

if (secret_question == '1' && another_question.length < 1) messages.push('{$invalidMessage}');
JS;

		return $js;						
	}
	
	function clientValidateSecretAnswer()
	{
		$secret_question = get_class($this).'_secret_question';
		$secret_answer = get_class($this).'_secret_answer';
		$invalidMessage=Yii::t('view', 'Bạn cần nhập câu trả lời ==.');

		$js = <<<JS
var secret_question = $('#{$secret_question}').val();
var secret_answer = $('#{$secret_answer}').val();

if (secret_question != '0' && secret_answer.length < 1) messages.push('{$invalidMessage}');
JS;

		return $js;		
	}
	
	function rules()
	{
		return array(
			array('password', 'required',
					'message'=>Yii::t('view', 'Bạn phải nhập mật khẩu'),),
			array('password', 'checkPassword','invalidMessage'=>Yii::t('view','Sai mật khẩu'),),
			array('secret_answer','validateSecretAnswer','clientValidate'=>'clientValidateSecretAnswer',
            			'invalidMessage'=>Yii::t('view', 'Bạn cần nhập câu trả lời.')),
            array('another_question','validateAnotherQuestion','clientValidate'=>'clientValidateAnotherQuestion',
            			'invalidMessage'=>Yii::t('view', 'Bạn cần nhập câu hỏi của bạn.')),
		);
	}  		  
  }
?>
