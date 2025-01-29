<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "historicalRelated".
 *
 * @property int $histId1
 * @property int $histId2
 *
 * @property HistoricalFact $histId10
 * @property HistoricalFact $histId20
 */
class HistoricalRelated extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historicalRelated';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['histId1', 'histId2'], 'required'],
            [['histId1', 'histId2'], 'integer'],
            [['histId1', 'histId2'], 'unique', 'targetAttribute' => ['histId1', 'histId2']],
            [['histId1'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['histId1' => 'id']],
            [['histId2'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['histId2' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'histId1' => 'Hist Id1',
            'histId2' => 'Hist Id2',
        ];
    }

    /**
     * Gets query for [[HistId10]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistId10()
    {
        return $this->hasOne(HistoricalFact::className(), ['id' => 'histId1']);
    }

    /**
     * Gets query for [[HistId20]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistId20()
    {
        return $this->hasOne(HistoricalFact::className(), ['id' => 'histId2']);
    }
}
