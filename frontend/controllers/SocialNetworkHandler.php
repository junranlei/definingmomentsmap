<?php

namespace frontend\controllers;

use Da\User\Event\SocialNetworkAuthEvent;
use Da\User\Factory\MailFactory;
use Da\User\Model\User;
use Da\User\Service\UserCreateService;
use Yii;
use yii\base\BaseObject;

/**
 * SocialNetworkHandler handles the social network authentication events.
 */
class SocialNetworkHandler extends BaseObject
{
    /**
     * @param SocialNetworkAuthEvent $event
     */
    public static function beforeAuthenticate($event)
    {
        return $event->account->save(false);
    }
}