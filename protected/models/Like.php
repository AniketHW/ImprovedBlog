<?php

 /* @property string $id
 /* @property string $user_id
 /* @property string $post_id
 /* @property integer $status
 /* @property integer $created_at
 /* @property integer $updated_at
 */
 class Like extends CActiveRecord
 {

 	const STATUS_LIKED =    1;
 	const STATUS_DISLIKED = 2;

 	public static function model($className=__CLASS__) {
 		return parent::model($className);
 	}

 	public function tableName() {
 		return 'like';
 	}

 	public function rules() {
 		return array(
 			array('user_id, post_id', 'required'),
 			array('status, created_at, updated_at', 'numerical', 'integerOnly'=>true),
 			array('status', 'unsafe'),
 			array('user_id, post_id', 'length', 'max'=>11),
 			);
 	}

 	public function relations() {
 		return array(
 			'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
 			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
 			);
 	}

 	public function scopes() {
 		return array(
 			'active' => array('condition'=>'t.status = 1'),
 			);
 	}

 	public function beforeSave() {
 		if($this->isNewRecord) { 
 			$this->status = self::STATUS_LIKED;
 			$this->created_at = time();
 		}
 		$this->updated_at = time();
 		return parent::beforeSave();
 	}

 	public function updateColumns($column_value_array) {
 		$column_value_array['updated_at'] = time();
 		foreach($column_value_array as $column_name => $column_value)
 			$this->$column_name = $column_value;
 		$this->update(array_keys($column_value_array));
 	}

 	public static function create($attributes) {
 		$model = new Like;
 		$model->attributes = $attributes;
 		$model->save();
 		return $model;
 	}
 }