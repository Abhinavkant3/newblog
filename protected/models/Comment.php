<?php

/* @property string $id
/* @property string $user_id
/* @property integer $post_id
/* @property string $create_comment
/* @property integer $status
/* @property integer $created_at
/* @property integer $updated_at
*/
class Comment extends CActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_DEACTIVATED = 2;
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'comment';
	}

	public function rules() {
		return array(
			array('user_id', 'required'),
			array('post_id, status, created_at, updated_at', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>11),
			array('create_comment', 'length', 'max'=>255),
			);
	}

	public function relations() {
		return array(
			'users' => array(self::BELONGS_TO,'User','user_id'),
			'posts' => array(self::BELONGS_TO,'Post','post_id'),
			
			);
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
		$model = new Comment;
		$model->attributes = $attributes;
		$model->save();
		return $model;
	}
}