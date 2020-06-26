<?php
namespace common\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class HistdisableRule extends Rule
{
    
    public $name = "canDisableHist";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(isset($params['hist'])){
            $hist=$params['hist'];
           
            $isSysAdmin = \Yii::$app->user->can("SysAdmin");
            //is owner or not?
            $assignedUsers1 = $hist->users1;
            $isOwner = (array_search($user, array_column($assignedUsers1, 'id'))!==FALSE);
            // linked to more than 1 map
            $mapLinks = $hist->historicalMapLinks;
            $isLinkedO1 = ((is_array($mapLinks) && count($mapLinks)>1)!==FALSE);
            return ($isSysAdmin || ($isOwner && !$isLinkedO1));
        }else{
            return false;
        }
    }
}

?>