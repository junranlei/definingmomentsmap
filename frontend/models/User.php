<?php
namespace frontend\models;
use yii\behaviors\TimestampBehavior;
use Da\User\Model\User as BaseUser;
/**
* @property int $publicPermission
* @property int $status
*/
/**
 * User model
 */
class User extends BaseUser
{
    public $mapCount;

    public $histCount;

    public $public_email;
    /**
     * {@inheritdoc}
     *  
     */
   /* public function behaviors()
    {
        $behaviors = [
            TimestampBehavior::class,
             //add audit log
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];

        if ($this->module->enableGdprCompliance) {
            $behaviors['GDPR'] = [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'gdpr_consent_date',
                'updatedAtAttribute' => false
            ];
        }
        return $behaviors;
        /*return [
            //add audit log
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }*/
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('usuario', 'Username'),
            'email' => \Yii::t('usuario', 'Email'),
            'registration_ip' => \Yii::t('usuario', 'Registration IP'),
            'unconfirmed_email' => \Yii::t('usuario', 'New email'),
            'password' => \Yii::t('usuario', 'Password'),
            'created_at' => \Yii::t('usuario', 'Registration time'),
            'confirmed_at' => \Yii::t('usuario', 'Confirmation time'),
            'last_login_at' => \Yii::t('usuario', 'Last login time'),
            'last_login_ip' => \Yii::t('usuario', 'Last login IP'),
            'password_changed_at' => \Yii::t('usuario', 'Last password change'),
            'password_age' => \Yii::t('usuario', 'Password age'),
            'mapCount'=>'Map Count',
            'histCount'=>'Historical Fact Count'
        ];
    }
    /**
     * Gets query for my [[Maps]].
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getMymaps($status=1)
    {
        return $this->hasMany(Map::className(), ['id' => 'mapId'])->viaTable('mapAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 1]);
            }
        )->andOnCondition(['status' => $status]);
    }
    /**
     * Gets query for my [[Maps]].
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getMap($status=1)
    {
        return $this->hasMany(Map::className(), ['id' => 'mapId'])->viaTable('mapAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['mapAssign.type' => 1]);
            }
        )->andOnCondition(['map.status' => $status]);
    }
    /**
     * Gets query for my [[Maps]] count.
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getMapcount($status=1)
    {
        return $this->getMymaps($status=1)->count();
    }

    /**
     * Gets query for assigned [[Maps]].
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedmaps($status=1)
    {
        return $this->hasMany(Map::className(), ['id' => 'mapId'])->viaTable('mapAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 2]);
            }
        )->andOnCondition(['status' => $status]);
    }
     /**
     * Gets query for my [[Historicalfacts]].
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getMyhists($status=1)
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('historicalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 1]);
            }
        )->andOnCondition(['status' => $status]);
    }

     /**
     * Gets query for my [[Historicalfacts]].
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricalfact($status=1)
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('historicalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['historicalAssign.type' => 1]);
            }
        )->andOnCondition(['historicalFact.status' => $status]);
    }
     /**
     * Gets query for my [[Historicalfacts]] count.
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getHistcount($status=1)
    {
        return $this->getMyhists($status=1)->count();
    }

    /**
     * Gets query for assigned [[Historicalfacts]].
     * @param int $status default 1 enabled
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedhists($status=1)
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('historicalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 2]);
            }
        )->andOnCondition(['status' => $status]);
    }

}