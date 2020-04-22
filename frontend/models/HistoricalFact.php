<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "historicalFact".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $date
 * @property string $dateEnded
 * @property string $timeCreated
 * @property string $urls
 * @property int $mainMediaId
 *
 * @property Feature[] $features
 * @property HistoricalAssign[] $historicalAssigns
 * @property User[] $users
 * @property HistoricalMapLink[] $historicalMapLinks
 * @property Map[] $maps
 * @property HistoricalMediaLink[] $historicalMediaLinks
 * @property Media[] $media
 */
class HistoricalFact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historicalFact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'description', 'date', 'dateEnded', 'urls', 'mainMediaId'], 'required'],
            [['id', 'mainMediaId'], 'integer'],
            [['description', 'urls'], 'string'],
            [['date', 'dateEnded', 'timeCreated'], 'safe'],
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
            'date' => 'Date',
            'dateEnded' => 'Date Ended',
            'timeCreated' => 'Time Created',
            'urls' => 'Urls',
            'mainMediaId' => 'Main Media ID',
        ];
    }

    /**
     * Gets query for [[Features]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFeatures()
    {
        return $this->hasMany(Feature::className(), ['histId' => 'id']);
    }

    /**
     * Gets query for [[HistoricalAssigns]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalAssigns()
    {
        return $this->hasMany(HistoricalAssign::className(), ['histId' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])->viaTable('historicalAssign', ['histId' => 'id']);
    }

    /**
     * Gets query for [[HistoricalMapLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalMapLinks()
    {
        return $this->hasMany(HistoricalMapLink::className(), ['histId' => 'id']);
    }

    /**
     * Gets query for [[Maps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMaps()
    {
        return $this->hasMany(Map::className(), ['id' => 'mapId'])->viaTable('historicalMapLink', ['histId' => 'id']);
    }

    /**
     * Gets query for [[HistoricalMediaLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalMediaLinks()
    {
        return $this->hasMany(HistoricalMediaLink::className(), ['histId' => 'id']);
    }

    /**
     * Gets query for [[Media]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(Media::className(), ['id' => 'mediaId'])->viaTable('historicalMediaLink', ['histId' => 'id']);
    }
}
