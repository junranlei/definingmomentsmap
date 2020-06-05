<?php
return [
    'name'=>'Defining Moments Map',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //usurio
        'authManager' => [
            'class' => 'Da\User\Component\AuthDbManagerComponent'
        ],
    ],
    'modules' => [
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module'
            
        ],
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'db' => 'db', 
            'accessRoles' => 'SysAdmin',
            'panels' => [
                'audit/request',
                'audit/error',
                'audit/trail',
                'audit/mail',
                'audit/javascript',
                //'app/views'
                //'app/views' => [
                    //'class' => 'app\panels\ViewsPanel',
                    // ...
                //],
            ],
        ]
        //'audit' => 'bedezign\yii2\audit\Audit',
        //'accessRoles' => null,
    ],
];
