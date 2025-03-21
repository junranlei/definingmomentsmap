<?php 
// events.php file

use Da\User\Controller\AdminController;
use Da\User\Controller\SettingsController;
use Da\User\Event\UserEvent;
use Da\User\Model\User;
//use frontend\models\User;
use yii\base\Event;
use yii\web\ForbiddenHttpException;

// This will happen at the controller's level
/*Event::on(AdminController::class, UserEvent::EVENT_AFTER_CREATE, function (UserEvent $event) {
    $user = $event->getUser();

    // ... your logic here
});*/

// This will happen at the model's level
Event::on(User::class, UserEvent::EVENT_AFTER_CREATE, function (UserEvent $event) {

    $user = $event->getUser();
    $auth = Yii::$app->authManager;
    $defaultRole = $auth->getRole("SysAuthor");
    $auth->assign($defaultRole, $user->id);

    // ... your logic here
});
Event::on(User::class, UserEvent::EVENT_AFTER_REGISTER, function (UserEvent $event) {

    $user = $event->getUser();
    $auth = Yii::$app->authManager;
    $defaultRole = $auth->getRole("SysAuthor");
    $auth->assign($defaultRole, $user->id);

    // ... your logic here
});
// This will happen at the model's level
/*Event::on(SettingsController::class, UserEvent::EVENT_BEFORE_DELETE, function (UserEvent $event) {

    $user = $event->getUser();
    $auth = Yii::$app->authManager;
    $defaultRole = $auth->getRole("SysAuthor");
    $auth->revoke($defaultRole,$user->id);
    $auth->revokeAll($user->id);

    // ... your logic here
});*/

Event::on(AdminController::class, UserEvent::EVENT_BEFORE_DELETE, function (UserEvent $event) {
    $user = $event->getUser();
    //check map hist
    $mapCount1=$user->getMapcount();
    $mapCount0=$user->getMapcount(0);
    $histCount1=$user->getHistcount();
    $histCount0=$user->getHistcount(0);
    if($mapCount1>0||$mapCount0>0||$histCount1>0||$histCount0>0){
        $message="This user can't be deleted, there are linked maps or historicl facts.";
        throw new ForbiddenHttpException($message);
    }
    //remove roles
    
    $auth = Yii::$app->authManager;
    $defaultRole = $auth->getRole("SysAuthor");
    $auth->revoke($defaultRole,$user->id);
    $auth->revokeAll($user->id);

    // ... your logic here
});

