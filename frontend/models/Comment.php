<?php

namespace app\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $content
 * @property integer $user_id
 * @property integer $article_id
 * @property integer $created_at
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'user_id', 'article_id', 'created_at'], 'required'],
            [['user_id', 'article_id', 'created_at'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => 'Content',
            'user_id' => 'User ID',
            'article_id' => 'Article ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 关联User表
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
}
