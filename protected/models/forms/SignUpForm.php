<?php

/**
* @property string $name
* @property string $dob
*/
class SignUpForm extends CFormModel
{
	public $first_name;
	public $last_name;
	public $password;
	public $confirmed_password;
	public $email;
	public $phone;
	public $gender;
	public $dobD;
	public $dobM;
	public $dobY;
	public $address;
	public $city_id;
	public $captcha;
	public $agreeTermOfService;

	function getName()
	{
		return implode(' ', array(trim($this->first_name), trim($this->last_name)));
	}

	function getDoB()
	{
		return sprintf('%04d/%02d/%02d', $this->dobY, $this->dobM, $this->dobD);
	}

	/**
	* @return Acc
	*/
	function createAccObject()
	{
		$acc = new Acc();

		$acc->first_name = $this->first_name;
		$acc->last_name = $this->last_name;
		$acc->email = $this->email;
		$acc->phone = $this->phone;
		$acc->gender = $this->gender;
		$acc->dob = $this->dob;
		$acc->address = $this->address;
		$acc->city_id = $this->city_id;

		return $acc;
	}

	function attributeLabels()
	{
		return array(
			'email'=>Yii::t('view', 'Email'),
			'phone'=>Yii::t('view', 'Điện thoại di động'),
			'password'=>Yii::t('view', 'Mật khẩu'),
			'confirmed_password'=>Yii::t('view', 'Gõ lại mật khẩu'),
			'gender'=>Yii::t('view', 'Giới tính'),
			'dob'=>Yii::t('view', 'Ngày sinh'),
			'address'=>Yii::t('view', 'Địa chỉ'),
			'city_id'=>Yii::t('view', 'Thành phố'),
			'captcha'=>Yii::t('view', 'Xác nhận bạn không phải robot'),
			'agreeTermOfService'=>
				Yii::t('view', 'Tôi đồng ý với <a href=":tosHref" target="_blank">Điều khoản dịch vụ</a> và <a href=":ppHref" target="_blank">Chính sách bảo mật</a> của Vật Giá.', array(
					':tosHref'=>'#',
					':ppHref'=>'#',
				)),
		);
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
	
	function validateConfirmedPassword($field, $params)
	{
		if ($this->password != $this->confirmed_password) {
			$this->addError('confirmed_password', $params['invalidMessage']);
			return;
		}

	}
	
	function clientValidateConfirmedPassword()
	{
		$password = get_class($this).'_password';
		$rePassword = get_class($this).'_confirmed_password';
		$invalidMessage=Yii::t('view', 'Bạn cần nhập lại đúng mật khẩu.');

		$js = <<<JS
var password = $('#{$password}').val();
var rePassword = $('#{$rePassword}').val();

if (password != rePassword) messages.push('{$invalidMessage}');
JS;

		return $js;
	}

	function validatePassword($field,$params)
	{
		if(strlen($this->password) < 8){
			$this->addError('password',$params['invalidMessage']);
			return;
		}
	}
	
	function clientValidatePassword()
	{
		$password = get_class($this).'_password';
		$invalidMessage=Yii::t('view', 'Mật khẩu ít nhất 8 ký tự.');
		$js = <<<JS
var password = $('#{$password}').val();

if (password.length < 8) messages.push('{$invalidMessage}');
JS;

		return $js;		
	}
	
	function validateDate()
	{
		if (empty($this->dobD) || empty($this->dobM) || empty($this->dobY)) {
			$this->addError('dob', Yii::t('view', 'Bạn không thể để trống ngày sinh.'));
			return;
		}
	}

	function rules()
	{
		$captchaRule = array(
				array('captcha', 'ext.common.recaptcha.EReCaptchaValidator', 
               		'privateKey'=>'6Lc_YNYSAAAAADl9meEKpYWaA2RRrhaZG2sVUQwV')
               	);
		
		$rule = array(
			array('first_name, last_name, email, phone, address', 'filter', 'filter'=>'trim'),
			array('phone', 'filter', 'filter'=>array('TextHelper','normalizePhoneNumber')),
			array('phone, email', 'unique', 'className'=>'Acc'),
			array('email', 'email', 'checkMX'=>true,),
			array('email, phone, address, city_id, password, confirmed_password', 'required',
				'message'=>Yii::t('view', 'Bạn không thể để trống {attribute}'),
			),
			array('gender', 'in', 'range'=>array(Acc::GENDER_MALE, Acc::GENDER_FEMALE, Acc::GENDER_OTHER), 'allowEmpty'=>false,
				'message'=>Yii::t('view', 'Bạn cần chọn giới tính.')
			),
			array('first_name, last_name, name', 'validateName', 'clientValidate'=>'clientValidateName',
				'emptyMessage'=>Yii::t('view', 'Bạn không thể để trống tên.'),
				'invalidMessage'=>Yii::t('view', 'Bạn cần điền đúng tên.')
			),
			array('dob', 'validateDate'),
			array('dob', 'date', 'allowEmpty'=>false, 'format'=>'yyyy/MM/dd',
				'message'=>Yii::t('view', 'Có vẻ bạn điền ngày sinh chưa đúng.')
			),
        	array('agreeTermOfService', 'required', 'requiredValue'=>1,
            	'message'=>Yii::t('message', 'Để sử dụng dịch vụ, bạn cần đồng ý với điều khoản dịch vụ của chúng tôi.'),
        	),
        	
            array('password','validatePassword','clientValidate'=>'clientValidatePassword',
            			'invalidMessage'=>Yii::t('view', 'Mật khẩu ít nhất 8 ký tự.'),),
            array('confirmed_password','validateConfirmedPassword','clientValidate'=>'clientValidateConfirmedPassword',
            		'invalidMessage'=>Yii::t('view', 'Bạn cần nhập lại đúng mật khẩu.')),
		);
		if(!(isset($_POST['ajax']) && $_POST['ajax']==='signUp-form'))
			$rule = CMap::mergeArray($rule, $captchaRule);
		return $rule;
	}
}