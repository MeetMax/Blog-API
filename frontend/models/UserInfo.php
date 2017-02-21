<?php

namespace frontend\models;
use  \yii\db\ActiveRecord;
class UserInfo extends ActiveRecord
{
    public static function tableName()
    {
        return '{{admin}}';
    }
    public function rules()
    {
        return [
            [['username','password'],'string']
        ];
    }
}