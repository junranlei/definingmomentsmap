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
 * @property string $externalId
 * @property int $visible
 * @property int $mapId
 * @property string $date
* @property int $status
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
     *  
     */
    public function behaviors()
    {
        return [
            //add audit log
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'visible', 'mapId', 'date'], 'required'],
            [['type', 'visible', 'mapId','status'], 'integer'],
            [['description'], 'string'],
            [['date', 'externalId'], 'safe'],
            [['title', 'nameOrUrl','externalId'], 'string', 'max' => 255],
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
            'title' => 'Display Title',
            'description' => 'Description',
            'type' => 'Type',
            'nameOrUrl' => 'Service Url',
            'externalId' => 'Service Layer Name',
            'visible' => 'Visible',
            'mapId' => 'Map ID',
            'date' => 'Date',
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
