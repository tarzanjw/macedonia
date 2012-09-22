<?php

/**
 * This is the model class for table "openid_allowed_realm".
 *
 * The followings are the available columns in table 'openid_allowed_realm':
 * @property string $id
 * @property string $realm
 * @property integer $enable
 * @property string $type
 * @property string $created_time
 * @property string $last_modified_time
 */
class OpenIDRealm extends CActiveRecord
{
    const TYPE_ALLOW = 'ALLOW';
    const TYPE_DENY = 'DENY';
    const TYPE_DEPEND_ON_ACCOUNT = 'DEPEND_ON_ACCOUNT';

    public function behaviors(){
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_time',
				'updateAttribute' => null,
			)
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OpenIDRealm the static model class
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
		return 'openid_realm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('realm, type, enable', 'required'),
			array('enable', 'numerical', 'integerOnly'=>true),
			array('realm', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, realm, enable, created_time, last_modified_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'realm' => 'Realm',
			'enable' => 'Enable',
			'created_time' => 'Created Time',
			'last_modified_time' => 'Last Modified Time',
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
		$criteria->compare('realm',$this->realm,true);
		$criteria->compare('enable',$this->enable);
		$criteria->compare('created_time',$this->created_time,true);
		$criteria->compare('last_modified_time',$this->last_modified_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}