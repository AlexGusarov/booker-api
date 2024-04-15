<?php

namespace app\models;

use yii\db\ActiveRecord;

class Language extends ActiveRecord
{
    public static function tableName()
    {
        return 'languages';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }
}
