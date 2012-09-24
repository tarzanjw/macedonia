<?php

/**
 * This is the model class for table "http_conn_log".
 *
 * The followings are the available columns in table 'http_conn_log':
 * @property string $id
 * @property string $request_method
 * @property string $request_url
 * @property string $response_code
 * @property string $src_ip
 * @property string $dst_ip
 * @property string $request_headers
 * @property string $response_headers
 * @property string $request_time
 * @property string $post_data
 * @property string $response_data
 * @property string $duration
 */
class RestApiLog extends CActiveRecord
{
	function init()
	{
		parent::init();
		
		$this->request_time = new CDbExpression('FROM_UNIXTIME('.time().')');
	}

	function tableName() { assert(false); }

	function defaultScope()
	{
		return array(
			'order'=>'request_time DESC',
		);	
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('request_method, request_url, response_code, src_ip, request_headers, request_time', 'required'),
			array('request_method', 'length', 'max'=>16),
			array('response_code', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, requst_method, request_url, response_code, src_ip, dst_ip, request_headers, response_headers, request_time, post_data, response_data', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'request_method'=>'Request Method',
			'request_url'=>'Request Url',
			'response_code'=>'Response Code',
			'src_ip' => 'Src Ip',
			'dst_ip' => 'Dst Ip',
			'request_headers' => 'Request Headers',
			'response_headers' => 'Response Headers',
			'request_time' => 'Request Time',
			'post_data' => 'Post Data',
			'response_data' => 'Response Data',
			'duration' => 'Duration',
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
		$criteria->compare('src_ip',$this->src_ip,true);
		$criteria->compare('dst_ip',$this->dst_ip,true);
		$criteria->compare('request_headers',$this->request_headers,true);
		$criteria->compare('response_headers',$this->response_headers,true);
		$criteria->compare('request_time',$this->request_time,true);
		$criteria->compare('post_data',$this->post_data,true);
		$criteria->compare('response_data',$this->response_data,true);
		$criteria->compare('duration',$this->duration,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

			'pagination'=>array(
				'pageSize'=>50,
			),
		));
	}
}