<?php

/**
 * This is the model class for table "acc".
 *
 * The followings are the available columns in table 'acc':
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 * @property string $avatar
 * @property string $gender
 * @property string $dob
 * @property string $address
 * @property string $city_id
 * @property string $created_time
 * @property string $last_modified_time
 * @property string $status
 * @property string $sources
 * @property boolean $is_email_verified
 * @property boolean $is_phone_verified
 *
 * The followings are the available model relations:
 * @property AccAuth $auth
 * @property City $city
 *
 * @author Tarzan <hocdt85@gmail.com>
 */
class Acc extends CActiveRecord
{
    const GENDER_MALE = 'MALE';
    const GENDER_FEMALE = 'FEMALE';
    const GENDER_OTHER = 'OTHER';

    const SOURCE_VATGIA = 'VAT_GIA';
    const SOURCE_GOOGLE = 'GOOGLE';
    const SOURCE_FACEBOOK = 'FACEBOOK';

    const STATUS_NORMAL='NORMAL';
    const STATUS_LOCKED='LOCKED';
    const STATUS_NOT_ACTIVATED_YET='NOT ACTIVATED YET';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Acc the static model class
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
		return 'acc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('first_name, last_name', 'required'),
			array('first_name, last_name, email, phone', 'length', 'max'=>255),
			array('gender', 'length', 'max'=>6),
//			array('status', 'length', 'max'=>17),
			array('status', 'in', 'range'=>array(self::STATUS_NORMAL, self::STATUS_LOCKED, self::STATUS_NOT_ACTIVATED_YET)),
//			array('sources', 'length', 'max'=>8),
			array('avatar, dob, address, created_time, last_modified_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, first_name, last_name, email, phone, avatar, gender, dob, address, city, created_time, last_modified_time, status, sources, is_email_verified, is_phone_verified', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'auth' => array(self::HAS_ONE, 'AccAuth', 'id'),
			'city'=>array(self::BELONGS_TO, 'City', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'email' => 'Email',
			'phone' => 'Phone',
			'avatar' => 'Avatar',
			'gender' => 'Gender',
			'dob' => 'Birthday',
			'address' => 'Address',
			'city_id' => 'City',
			'created_time' => 'Created Time',
			'last_modified_time' => 'Last Modified Time',
			'status' => 'Status',
			'sources' => 'Sources',
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
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('gender',$this->gender,true);
		$criteria->compare('dob',$this->dob,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('last_modified_time',$this->last_modified_time,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('sources',$this->sources,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}