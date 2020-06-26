<?php
namespace common\rbac;

use yii\rbac\Rule;
use frontend\models\Map;

/**
 * Checks if authorID matches user passed via params
 */
class MapdisableRule extends Rule
{
    
    public $name = "canDisableMap";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(isset($params['map'])){
            $map=$params['map'];
            $isSysAdmin = \Yii::$app->user->can("SysAdmin");
            //is owner or not?
            $assignedUsers1 = $map->users1;
            $isOwner = (array_search($user, array_column($assignedUsers1, 'id'))!==FALSE);
            
            return ($isSysAdmin||$isOwner);
        }else{
            return false;
        }
    }
}

?>