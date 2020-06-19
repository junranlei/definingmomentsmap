<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "historicalMediaLink".
 *
 * @property int $histId
 * @property int $mediaId
 *
 * @property HistoricalFact $hist
 * @property Media $media
 */
class HistoricalMediaLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historicalMediaLink';
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
            [['histId', 'mediaId'], 'required'],
            [['histId', 'mediaId'], 'integer'],
            [['histId', 'mediaId'], 'unique', 'targetAttribute' => ['histId', 'mediaId']],
            [['histId'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['histId' => 'id']],
            [['mediaId'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['mediaId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'histId' => 'Hist ID',
            'mediaId' => 'Media ID',
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
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'mediaId']);
    }
}
