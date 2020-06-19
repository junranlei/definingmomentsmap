<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "historicalMapLink".
 *
 * @property int $histId
 * @property int $mapId
 *
 * @property HistoricalFact $hist
 * @property Map $map
 */
class HistoricalMapLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historicalMapLink';
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
            [['histId', 'mapId'], 'required'],
            [['histId', 'mapId'], 'integer'],
            [['histId', 'mapId'], 'unique', 'targetAttribute' => ['histId', 'mapId']],
            [['histId'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['histId' => 'id']],
            [['mapId'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['mapId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'histId' => 'Hist ID',
            'mapId' => 'Map ID',
        ];
    }

    /**
     * Gets query for [[Hist]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHist()
    {
        return $this->hasOne(HistoricalFact::className(), ['id' => 'histId']);
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
