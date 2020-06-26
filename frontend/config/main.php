<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    //'homeUrl'=>array('site/map'),
    'basePath' => dirname(__DIR__),
    //'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    //usuario
    'bootstrap' => ['user'],
    'modules' => [
        //usuario
        'user' => [
            'class' => Da\User\Module::className(),
            'classMap' => [
                'User' => frontend\models\User::class,
                'Profile' => frontend\models\Profile::class,
            ],
            'administratorPermissionName' => 'SysAdmin',
            //'administrators' => ['admin'],
            'enableRegistration'=>true,
            'allowPasswordRecovery'=>true,
            'generatePasswords' => true,
            'enableEmailConfirmation'=>true,
            'controllerMap' => [
                'profile' => [
                    'class' => 'frontend\controllers\ProfileController',
                ],
                'security' => [
                    'class' => 'Da\User\Controller\SecurityController',
                    'on beforeAuthenticate' => ['frontend\controllers\SocialNetworkHandler', 'beforeAuthenticate']
                ],

            ]
        ],   
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@Da/User/resources/views/profile' => '@app/views/profile',
                ],
            ],
        ],
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],*/
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
