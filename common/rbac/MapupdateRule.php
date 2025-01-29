<?php
namespace common\rbac;

use yii\rbac\Rule;
use frontend\models\Map;

/**
 * Checks if authorID matches user passed via params
 */
class MapupdateRule extends Rule
{
    
    public $name = "isEditableMap";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        //return isset($params['post']) ? $params['post']->createdBy == $user : false;
        if(isset($params['map'])){
            $map=$params['map'];
            $isSysAdmin = \Yii::$app->user->can("SysAdmin");
            //everyone can edit return true
            if($map->publicPermission)
                return true;
            //is owner or assigned user or not?
            $assignedUsers12 = $map->users;
            //print_r('<script>alert("'.json_encode(array_column($assignedUsers12, 'id')).'");</script>');exit;      
            return ($isSysAdmin || array_search($user, array_column($assignedUsers12, 'id'))!==FALSE);
        }else{
            return false;
        }
    }
}

?>