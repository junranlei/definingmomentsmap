<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "mapAssign".
 *
 * @property int $mapId
 * @property int $userId
 * @property string $assignedTime
 * @property string $updatedTime
 * @property int $type
 * @property string $qrCode
 *
 * @property Map $map
 * @property User $user
 */
class MapAssign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mapAssign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mapId', 'userId', 'type'], 'required'],
            [['mapId', 'userId', 'type'], 'integer'],
            [['assignedTime', 'updatedTime'], 'safe'],
            [['qrCode'], 'string', 'max' => 255],
            [['mapId', 'userId'], 'unique', 'targetAttribute' => ['mapId', 'userId']],
            [['mapId'], 'exist', 'skipOnError' => true, 'targetClass' => Map::className(), 'targetAttribute' => ['mapId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mapId' => 'Map ID',
            'userId' => 'User ID',
            'assignedTime' => 'Assigned Time',
            'updatedTime' => 'Updated Time',
            'type' => 'Type',
            'qrCode' => 'Qr Code',
        ];
    }

    /**
     * Gets query for [[Map]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMap()
    {
        return $this->hasOne(Map::className(), ['id' => 'mapId']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
