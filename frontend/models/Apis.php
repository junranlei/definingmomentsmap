<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "apis".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $url
 * @property string|null $apikey
 */
class Apis extends \yii\db\ActiveRecord
{
    public $jsonField;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'url'], 'required'],
            [['name', 'apikey'], 'string', 'max' => 100],
            [['description', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'url' => 'Url',
            'apikey' => 'API Key',
        ];
    }
}
