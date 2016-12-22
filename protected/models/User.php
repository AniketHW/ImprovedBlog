<?php

 /* @property string $id
 /* @property string $name
 /* @property string $email
 /* @property string $password
 /* @property integer $status
 /* @property integer $created_at
 /* @property integer $updated_at
 */
 class User extends CActiveRecord
 {

 	const STATUS_ACTIVE =   1;
 	const STATUS_INACTIVE = 2;

 	public static function model($className=__CLASS__) {
 		return parent::model($className);
 	}

 	public function tableName() {
 		return 'user';
 	}

 	public function rules() {
 		return array(
 			array('name, email, password', 'required'),
 			array('status, created_at, updated_at', 'numerical', 'integerOnly'=>true),
 			array('status', 'unsafe'),
 			array('name, email, password', 'length', 'max'=>255),
 			);
 	}

 	public function relations() {
 		return array(
 			'posts' =>          array(self::HAS_MANY, 'Post', 'user_id'),
 			'comments' =>       array(self::HAS_MANY, 'Comment', 'user_id'),
 			'likes' =>          array(self::HAS_MANY, 'Like', 'user_id'),
 			'post_count' =>	    array(self::STAT, 'Post', 'user_id'),
 			'comment_count' =>  array(self::STAT, 'Comment', 'user_id'),
 			'like_count' => 	array(self::STAT, 'Like', 'user_id'),
 			);
 	}

 	public function scopes() {
 		return array(
 			'active' => array('condition'=>'t.status = 1'),
 			);
 	}

 	public function deactivate() { 		
		$this->status = self::STATUS_INACTIVE;
		$this->save();
	}

	public function activate() {
		$this->status = self::STATUS_ACTIVE;
		$this->save();
	}

 	public function beforeSave() {
 		if($this->isNewRecord) { 
 			$this->status = self::STATUS_ACTIVE;
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
 		$model = new User;
 		$model->attributes = $attributes;
 		$model->save();
 		return $model;
 	}
 }