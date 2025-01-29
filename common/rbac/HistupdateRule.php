<?php
namespace common\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class HistupdateRule extends Rule
{
    
    public $name = "isEditableHist";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(isset($params['hist'])){
            $isSysAdmin = \Yii::$app->user->can("SysAdmin");
            $hist=$params['hist'];
            //everyone can edit return true
            if($hist->publicPermission)
                return true;
            //is owner or assigned user or not?
            $assignedUsers12 = $hist->users;
            return ($isSysAdmin || array_search($user, array_column($assignedUsers12, 'id'))!==FALSE);
        }else{
            return false;
        }
    }
}

?>