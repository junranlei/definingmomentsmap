<?php
namespace frontend\models;

use Da\User\Model\User as BaseUser;

class User extends BaseUser
{
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