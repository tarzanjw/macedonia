<?php
  class SecretQuestionForm extends CFormModel
  {
  	public $_accModel;
  	public $password;
  	public $secret_question = 0;
  	public $another_question;
  	public $secret_answer;
  	public $secret_question_list;
  	
  	function init()
	{
		$this->getAccModel();
		$this->getSecretQuestionList();
		$this->setSecret();
	}
		
	function attributeLabels()
	{
		return array(
			'password'=>Yii::t('view', 'Mật khẩu').':',
			'secret_question'=>Yii::t('view', 'Chọn câu hỏi bảo mật').':',
			'secret_answer'=>Yii::t('view', 'Trả lời câu hỏi bảo mật').':',
			'another_question'=>Yii::t('view', 'Câu hỏi của bạn').':',
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
  
  	public function getSecretQuestionList()
	{
		$secretQuestions = SecretQuestion::model()->getAllQuestionAsArray();  
		$secretQuestions = array_merge(array(0=>'-- không sử dụng câu hỏi bảo mật --'),$secretQuestions);
		$secretQuestions = array_merge($secretQuestions,array(1=>'-- Viết câu hỏi khác --'));
		$this->secret_question_list = $secretQuestions;
	}
	
	public function setSecret()
	{
		$accModel = $this->_accModel;
		if(!empty($accModel->auth->secret_answer)){
			if(!in_array($accModel->auth->secret_question,$this->secret_question_list)){
				$this->secret_question = 1;	
				$this->another_question = $accModel->auth->secret_question;
			}else $this->secret_question = $accModel->auth->secret_question;
		}else{
			$this->secret_answer = null;
			$this->secret_question = 0;	
		}
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
		$invalidMessage=Yii::t('view', 'Bạn cần nhập câu trả lời.');

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
