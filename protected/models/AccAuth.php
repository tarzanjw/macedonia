<?php

/**
 * This is the model class for table "acc_auth".
 *
 * The followings are the available columns in table 'acc_auth':
 * @property string $id
 * @property string $password
 * @property string $password_salt
 * @property string $secret_question
 * @property string $secret_answer
 *
 * The followings are the available model relations:
 * @property Acc $acc
 */
class AccAuth extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AccAuth the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'acc_auth';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id', 'required'),
			array('id', 'length', 'max'=>11),
			array('password, secret_answer', 'length', 'max'=>40),
			array('password_salt', 'length', 'max'=>32),
			array('secret_question', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, password, password_salt, secret_question, secret_answer', 'safe', 'on'=>'search'),
		);
	}

	/**
	* Import a plain password and then generate a salt, hash it by HMAC
	*
	* @param string $plainPassword
	*/
	function importPassword($plainPassword)
	{
		$this->password_salt = SecurityHelper::generateSalt(6);
		$this->password = SecurityHelper::hashPassword($plainPassword, $this->password_salt);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'acc' => array(self::BELONGS_TO, 'Acc', 'id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'password' => 'Password',
			'password_salt' => 'Password Salt',
			'secret_question' => 'Secret Question',
			'secret_answer' => 'Secret Answer',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('password_salt',$this->password_salt,true);
		$criteria->compare('secret_question',$this->secret_question,true);
		$criteria->compare('secret_answer',$this->secret_answer,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}