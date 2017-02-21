<?php

namespace app\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $cat_id
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'cat_id','user_id','updated_at','html'], 'required'],
            [['content'], 'string'],
            [['cat_id'], 'integer'],
            [['title'], 'string', 'max' => 75],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'cat_id' => 'Cat ID',
        ];
    }

    /**
     * 关联User表
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
    /**
     * 关联Category
     */
    public function getCategory(){
        return $this->hasOne(Category::className(),['id'=>'cat_id']);
    }
}
