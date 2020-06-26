<?php 
// events.php file

use Da\User\Controller\AdminController;
use Da\User\Event\UserEvent;
use Da\User\Model\User;
//use frontend\models\User;
use yii\base\Event;

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

