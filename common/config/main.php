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
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'Da\User\AuthClient\Facebook',
                    'clientId' => '310887836737434',
                    'clientSecret' => '26dea175c3b3e37a4919589870b1a5fc'
                ],
                'google' => [
                    'class' => 'Da\User\AuthClient\Google',
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                ],
                'github' => [
                    'class' => 'Da\User\AuthClient\GitHub',
                    'clientId' => 'a9f013e5286c82820ca1',
                    'clientSecret' => '4f7d3256219b30105d23b0ce8bfa0b0ecf5966aa',
                ],
                'twitter' => [
                      'class' => 'yii\authclient\clients\Twitter',
                        'attributeParams' => [
                            'include_email' => 'true'
                        ],
                        'consumerKey' => 'twitter_consumer_key',
                        'consumerSecret' => 'twitter_consumer_secret',
                ],
                'linkedin' => [
                    'class' => 'Da\User\AuthClient\Linkedin',
                    'clientId' => 'linkedin_client_id',
                    'clientSecret' => 'linkedin_client_secret',
                ]
            ]
        ]
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
