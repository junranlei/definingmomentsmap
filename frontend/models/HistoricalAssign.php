<?php

namespace frontend\models;
use Da\User\Model\User;

use Yii;

/**
 * This is the model class for table "historicalAssign".
 *
 * @property int $histId
 * @property int $userId
 * @property string $assignedTime
 * @property string $updatedTime
 * @property int $type
 * @property string $notes
 *
 * @property HistoricalFact $hist
 * @property User $user
 */
class HistoricalAssign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historicalAssign';
    }

    /**
     * {@inheritdoc}
     *  
     */
    public function behaviors()
    {
        return [
            //add audit log
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['histId', 'userId', 'type'], 'required'],
            [['histId', 'userId', 'type'], 'integer'],
            [['assignedTime', 'updatedTime'], 'safe'],
            [['notes'], 'string'],
            [['histId', 'userId'], 'unique', 'targetAttribute' => ['histId', 'userId']],
            [['histId'], 'exist', 'skipOnError' => true, 'targetClass' => HistoricalFact::className(), 'targetAttribute' => ['histId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'histId' => 'Hist ID',
            'userId' => 'User ID',
            'assignedTime' => 'Assigned Time',
            'updatedTime' => 'Updated Time',
            'type' => 'Type',
            'notes' => 'Notes',
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
