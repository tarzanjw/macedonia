<?php
  class ChangeInfoForm extends CFormModel
  {
  	public $_accModel;
  	public $email;
  	public $gender;
  	public $name;
  	public $first_name;
  	public $last_name;
  	public $dobD;
	public $dobM;
	public $dobY;
	public $birthday;
	public $address;
	public $city_id;
  	
  	function init()
	{
		$this->getAccModel();
		$this->getInfo();
	}
	
	function getName()
	{
		return implode(' ', array(trim($this->first_name), trim($this->last_name)));
	}
	
	function getDoB()
	{
		return sprintf('%04d/%02d/%02d', $this->dobY, $this->dobM, $this->dobD);
	}
	
	function setDoB()
	{
		return;
	}
		
	function attributeLabels()
	{
		return array(
			'email'=>Yii::t('view', 'Email'),
			'first_name'=>Yii::t('view', 'Họ đệm'),
			'last_name'=>Yii::t('view', 'Tên'),
			'gender'=>Yii::t('view', 'Giới tính'),
			'dob'=>Yii::t('view', 'Ngày sinh'),
			'birthday'=>Yii::t('view', 'Ngày sinh'),
			'address'=>Yii::t('view', 'Địa chỉ'),
			'city_id'=>Yii::t('view', 'Thành phố'),
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
	
	public function getInfo()
	{
		$dateInt = strtotime($this->_accModel->dob);
   		$birthDay = date('d-m-Y',$dateInt);
   		$this->birthday = $birthDay;
		$this->email = $this->_accModel->email;
		
		$this->first_name = $this->_accModel->first_name;
		$this->last_name = $this->_accModel->last_name;
		
		$this->city_id = $this->_accModel->city->id;
		$this->gender = $this->_accModel->gender;
		$this->address = $this->_accModel->address;
		
		$this->dobD = date('d',$dateInt);
		$this->dobM = date('m',$dateInt);
		$this->dobY = date('Y',$dateInt);
	}
	
	function validateName($field, $params)
	{
		if (empty($this->first_name) || empty($this->last_name)) {
			$this->addError('name', $params['emptyMessage']);
			return;
		}

		if (strlen($this->first_name)+strlen($this->last_name) < 3)
			$this->addError('name', $params['invalidMessage']);
	}

	function clientValidateName()
	{
		$iName = get_class($this).'_name';
		$iFirstName = get_class($this).'_first_name';
		$iLastName = get_class($this).'_last_name';
		$emptyMessage=Yii::t('view', 'Bạn không thể để trống tên.');
		$invalidMessage=Yii::t('view', 'Bạn cần điền đúng tên.');

		$js = <<<JS
var firstName = $('#{$iFirstName}').val();
var lastName = $('#{$iLastName}').val();
if (!firstName.length || !lastName.length) {
	messages.push('{$emptyMessage}');
	return;
}

if ((firstName.length + lastName.length) < 3) messages.push('{$invalidMessage}');
JS;

		return $js;
	}
	
	function validateDate()
	{
		if (empty($this->dobD) || empty($this->dobM) || empty($this->dobY)) {
			$this->addError('dob', Yii::t('view', 'Bạn cần điền ngày sinh.'));
			return;
		}
	}
	
	function clientValidateDate()
	{
		$dobD= get_class($this).'_dobD';
		$dobM = get_class($this).'_dobM';
		$dobY = get_class($this).'_dobY';
		$emptyMessage = Yii::t('view','Bạn cần điền Ngày sinh');
			$js = <<<JS
var dobD = $('#{$dobD}').val();
var dobM = $('#{$dobM}').val();
var dobY = $('#{$dobY}').val();
if (!dobD.length || !dobM.length || !dobY.length) {
	messages.push('{$emptyMessage}');
	return;
}
JS;

		return $js;
	}

	
	
	function rules()
	{
		return array(
			array('first_name, last_name, address', 'filter', 'filter'=>'trim'),
			array('address, city_id', 'required',
				'message'=>Yii::t('view', 'Bạn cần điền {attribute}'),
			),
			array('gender', 'in', 'range'=>array(Acc::GENDER_MALE, Acc::GENDER_FEMALE, Acc::GENDER_OTHER), 'allowEmpty'=>false,
				'message'=>Yii::t('view', 'Bạn cần chọn giới tính.')
			),
			array('first_name, last_name, name', 'validateName', 'clientValidate'=>'clientValidateName',
				'emptyMessage'=>Yii::t('view', 'Bạn cần điền tên.'),
				'invalidMessage'=>Yii::t('view', 'Bạn cần điền đúng tên.')
			),
			array('dobB, dobM, dobD, dob', 'validateDate','clientValidate'=>'clientValidateDate'),
			array('dob', 'date', 'allowEmpty'=>false, 'format'=>'yyyy/MM/dd',
				'message'=>Yii::t('view', 'bạn điền ngày sinh chưa đúng.')
			),

		);
	} 
	 
  }
?>
