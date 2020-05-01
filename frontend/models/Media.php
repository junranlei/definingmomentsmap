<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $type
 * @property string $nameOrUrl
 * @property int $right2Link
 * @property int $ownerId
 *
 * @property HistoricalMediaLink[] $historicalMediaLinks
 * @property HistoricalFact[] $hists
 */
class Media extends \yii\db\ActiveRecord
{

    /** upload files */
    public $files;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type', 'right2Link', 'ownerId'], 'required'],
            [['type', 'right2Link', 'ownerId'], 'integer'],
            [['description'], 'string'],
            [['title', 'nameOrUrl'], 'string', 'max' => 255],
            [['files'], 'file', 'skipOnEmpty' => true],
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
            'right2Link' => 'Right2 Link',
            'ownerId' => 'Owner ID',
        ];
    }

    /**
     * Gets query for [[HistoricalMediaLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalMediaLinks()
    {
        return $this->hasMany(HistoricalMediaLink::className(), ['mediaId' => 'id']);
    }

    /**
     * Gets query for [[Hists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHists()
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('historicalMediaLink', ['mediaId' => 'id']);
    }
}
