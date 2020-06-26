<?php
namespace common\rbac;

use yii\rbac\Rule;

/**
 * Checks if authorID matches user passed via params
 */
class ProfileupdateRule extends Rule
{
    
    public $name = "isEditableProfile";
    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        
        if(isset($params['profile'])){
            $profile=$params['profile'];
            $isSysAdmin = \Yii::$app->user->can("SysAdmin");
            $profileId = $profile->user_id;
            //check if user is sysadmin or the profile owner
            return ($isSysAdmin||($user==$profileId));
        }else{
            return false;
        }
    }
}

?>