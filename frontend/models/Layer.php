<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "layer".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $type
 * @property string $nameOrUrl
 * @property int $visible
 * @property int $mapId
 * @property string $date
 * @property string $dateEnded
 *
 * @property Map $map
 */
class Layer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'layer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'description', 'type', 'nameOrUrl', 'visible', 'mapId', 'date', 'dateEnded'], 'required'],
            [['id', 'type', 'visible', 'mapId'], 'integer'],
            [['description'], 'string'],
            [['date', 'dateEnded'], 'safe'],
            [['title', 'nameOrUrl'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['mapId'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['mapId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'type' => 'Type',
            'nameOrUrl' => 'Name Or Url',
            'visible' => 'Visible',
            'mapId' => 'Map ID',
            'date' => 'Date',
            'dateEnded' => 'Date Ended',
        ];
    }

    /**
     * Gets query for [[Map]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMap()
    {
        return $this->hasOne(Map::className(), ['id' => 'mapId']);
    }
}
