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
 * @property int $publicPermission
 * @property int $status
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
            [['title', 'description', 'right2Add'], 'required'],
            [['right2Add','publicPermission', 'status'], 'integer'],
            [['description'], 'string'],
            [['timeCreated', 'timeUpdated','assignedUsers'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'right2Add' => 'right2Add-Others can add historical facts',
            'publicPermission'=>'Everyone Can Edit'
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
     * Gets query for [[Layers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLayers()
    {
        return $this->hasMany(Layer::className(), ['mapId' => 'id']);
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

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers2()
    {
        return $this->hasMany(User::className(), ['id' => 'userId'])->viaTable('mapAssign', ['mapId' => 'id'], 
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
}
