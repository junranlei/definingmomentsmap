<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "map".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $timeCreated
 * @property string $timeUpdated
 * @property int $right2Add
 *
 * @property HistoricalMapLink[] $historicalMapLinks
 * @property HistoricalFact[] $hists
 * @property MapAssign[] $mapAssigns
 * @property User[] $users
 */
class Map extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'description', 'timeUpdated', 'right2Add'], 'required'],
            [['id', 'right2Add'], 'integer'],
            [['description'], 'string'],
            [['timeCreated', 'timeUpdated'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'timeCreated' => 'Time Created',
            'timeUpdated' => 'Time Updated',
            'right2Add' => 'Right2 Add',
        ];
    }

    /**
     * Gets query for [[HistoricalMapLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalMapLinks()
    {
        return $this->hasMany(HistoricalMapLink::className(), ['mapId' => 'id']);
    }

    /**
     * Gets query for [[Hists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHists()
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('historicalMapLink', ['mapId' => 'id']);
    }

    /**
     * Gets query for [[MapAssigns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMapAssigns()
    {
        return $this->hasMany(MapAssign::className(), ['mapId' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])->viaTable('mapAssign', ['mapId' => 'id']);
    }
}
