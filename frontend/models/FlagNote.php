<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "flagNote".
 *
 * @property int $id
 * @property int $flagId
 * @property int $userId
 * @property string $note
 *
 * @property Flag $flag
 * @property User $user
 */
class FlagNote extends \yii\db\ActiveRecord
{
    //pass model to Flag
    public $m;
    //pass model id to Flag
    public $mId;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flagNote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['note'], 'required'],
            [['flagId', 'userId'], 'integer'],
            [['m', 'mId'], 'safe'],
            [['note'], 'string', 'max' => 255],
            [['flagId'], 'exist', 'skipOnError' => true, 'targetClass' => Flag::className(), 'targetAttribute' => ['flagId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'flagId' => 'Flag ID',
            'userId' => 'User ID',
            'note' => 'Note',
        ];
    }

    /**
     * Gets query for [[Flag]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlag()
    {
        return $this->hasOne(Flag::className(), ['id' => 'flagId']);
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
