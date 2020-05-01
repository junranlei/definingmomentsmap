<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "feature".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $geojson
 * @property int $visible
 * @property int $histId
 *
 * @property HistoricalFact $hist
 */
class Feature extends \yii\db\ActiveRecord
{
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'visible', 'histId'], 'required'],
            [['visible', 'histId'], 'integer'],
            [['description', 'geojson'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['histId'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['histId' => 'id']],
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
            'geojson' => 'Geojson',
            'visible' => 'Visible',
            'histId' => 'Hist ID',
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
}
