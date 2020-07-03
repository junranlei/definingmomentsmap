<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "flag".
 *
 * @property int $id
 * @property string $model
 * @property int $modelId
 * @property int $times
 * @property string $timeCreated
 * @property string $timeUpdated
 *
 * @property Map $model0
 * @property HistoricalFact $model1
 * @property FlagNote[] $flagNotes
 */
class Flag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'modelId', 'times'], 'required'],
            [['modelId', 'times'], 'integer'],
            [['timeCreated', 'timeUpdated'], 'safe'],
            [['model'], 'string', 'max' => 20],
            [['modelId'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['modelId' => 'id']],
            [['modelId'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['modelId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'modelId' => 'Model ID',
            'times' => 'Times',
            'timeCreated' => 'Time Created',
            'timeUpdated' => 'Time Updated',
        ];
    }

    /**
     * Gets query for [[Model0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModel0()
    {
        return $this->hasOne(Map::className(), ['id' => 'modelId']);
    }

    /**
     * Gets query for [[Model1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModel1()
    {
        return $this->hasOne(HistoricalFact::className(), ['id' => 'modelId']);
    }

    /**
     * Gets query for [[FlagNotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlagNotes()
    {
        return $this->hasMany(FlagNote::className(), ['flagId' => 'id']);
    }
}
