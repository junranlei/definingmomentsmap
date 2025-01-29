<?php
namespace common\models;

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
     * Gets query for my [[HistoricalFacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMyhists()
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('HistoricalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 1]);
            }
        );
    }

    /**
     * Gets query for assigned [[HistoricalFacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignedhists()
    {
        return $this->hasMany(HistoricalFact::className(), ['id' => 'histId'])->viaTable('HistoricalAssign', ['userId' => 'id'], 
            function($query) {
                $query->onCondition(['type' => 2]);
            }
        );
    }

}