<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property int $width
 * @property int $height
 * @property int $user_id
 * @property string $data
 */
class ImageDb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['width', 'height', 'user_id', 'data'], 'required'],
            [['user_id'], 'integer',  'integerOnly' => true,],
            [['width', 'height',], 'integer', 'min' => 10, 'max' => 1000, 'integerOnly' => true,],
            [['data'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'width' => 'Width',
            'height' => 'Height',
            'user_id' => 'User ID',
            'data' => 'Data',
        ];
    }
}
