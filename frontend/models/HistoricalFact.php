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
 * @property int $right2Link
 * @property int $publicPermission
 * @property string $assignedUsers
 * @property int $status
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

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'date'], 'required'],
            ['date', 'date','format' => 'yyyy-mm-dd'],
            ['dateEnded', 'date','format' => 'yyyy-mm-dd'],
            [['mainMediaId','right2Link','publicPermission','status'], 'integer'],
            [['description', 'urls'], 'string'],
            [['date', 'dateEnded', 'timeCreated','assignedUsers'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['urls', 'validateUrls', 'skipOnEmpty' => true, 'skipOnError' => false]
        ];
    }
    public function validateUrls($attribute, $params)
    {
        if ($this->$attribute) {
            $urls = $this->$attribute;
            //if($urls!=null&&sizeof($urls)>0){
                $urlArray = explode(";",$urls);
                foreach($urlArray as $url){
                    if (!filter_var($url, FILTER_VALIDATE_URL)){
                        $this->addError($attribute, $this->$attribute.' is not a valid URL, make sure to include scheme, eg http://, https:// or ftp://, please check.');
                        return false;
                    }
                }
            //}
        }
    
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
            'urls' => 'URLs',
            'mainMediaId' => 'Main Media ID',
            'right2Link' => 'Others Can Link This Historical Fact',
            //'right2Link' => 'Permission to add this fact to other map',
            'publicPermission'=>'Everyone Can Edit'
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
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers2()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])->viaTable('historicalAssign', ['histId' => 'id'], 
            function($query) {
            $query->onCondition(['type' =>2]);
        });
    }

    /**
     * @return string
     */
    public function getAssignedUsers()
    {
        $users =  $this->users2;
        $ds = "";
        foreach($users as $user){
            if($user!=null&&$user->username!=null){
                if($ds!="")$ds=$ds.",";
                $ds = $ds.$user->username;
            }
        }
        return $ds;
    }
    
    /**
     * @return string
     */
    public function setAssignedUsers($ds)
    {
        $this->assignedUsers = $ds;
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
