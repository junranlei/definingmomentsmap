<?php
namespace frontend\models;
use yii\behaviors\TimestampBehavior;
use Da\User\Model\User as BaseUser;

class User extends BaseUser
{
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
     * Gets query for my [[Maps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMymaps()
    {
        return $this->hasMany(Map::className(), ['id' => 'mapId'])->viaTable('MapAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 1]);
            }
        );
    }

    /**
     * Gets query for assigned [[Maps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedmaps()
    {
        return $this->hasMany(Map::className(), ['id' => 'mapId'])->viaTable('MapAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 2]);
            }
        );
    }
     /**
     * Gets query for my [[Historicalfacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMyhists()
    {
        return $this->hasMany(Historicalfact::className(), ['id' => 'histId'])->viaTable('HistoricalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 1]);
            }
        );
    }

    /**
     * Gets query for assigned [[Historicalfacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedhists()
    {
        return $this->hasMany(Historicalfact::className(), ['id' => 'histId'])->viaTable('HistoricalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 2]);
            }
        );
    }

}