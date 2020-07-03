<?php
namespace common\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class MediadisableRule extends Rule
{
    
    public $name = "canDisableMedia";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if(isset($params['media'])){
            $media=$params['media'];
            $isSysAdmin = \Yii::$app->user->can("SysAdmin");
            //is owner or not?
            //$assignedUsers1 = $media->users1;
            $ownerId = $media->ownerId;
            // linked to more than 1 hist, check with status?
            $mediaLinks = $media->hists;
            $isLinkedO1 = (is_array($mediaLinks) && count($mediaLinks)>1);
            //print_r('<script>alert("'.json_encode(array_column($assignedUsers12, 'id')).'");</script>');exit;      
            return ($isSysAdmin||(($user==$ownerId) && !$isLinkedO1));
            
        }else{
            return false;
        }
    }
}

?>