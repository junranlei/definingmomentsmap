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
 * @property Map $modelMap
 * @property HistoricalFact $modelHist
 * @property Media $modelMedia
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
            [['timeCreated', 'timeUpdated','modelTitle'], 'safe'],
            [['model'], 'string', 'max' => 20],
            //[['modelId'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['modelId' => 'id']],
            //[['modelId'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['modelId' => 'id']],
            //[['modelId'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['modelId' => 'id']],
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
    public function getModelTitle(){
        if($this->model=="map"){
            return $this->modelMap->title;
        }
    }

    /**
     * Gets query for [[Map]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelMap()
    {
        return $this->hasOne(Map::className(), ['id' => 'modelId']);
    }

    /**
     * Gets query for [[HistoricalFact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelHist()
    {
        return $this->hasOne(HistoricalFact::className(), ['id' => 'modelId']);
    }

     /**
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelMedia()
    {
        return $this->hasOne(Media::className(), ['id' => 'modelId']);
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
