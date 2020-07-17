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
                    'clientId' => '663524167954-la7ehjrusadpb661vu1fnqfd573i4r5v.apps.googleusercontent.com',
                    'clientSecret' => 'tX1hV4P-QRD29jNQhKBimsrZ',
                    'returnUrl' => 'http://localhost/defining2/frontend/web/index.php?r=user/security/auth&authclient=google',
                ],
                'github' => [
                    'class' => 'Da\User\AuthClient\GitHub',
                    'clientId' => 'a9f013e5286c82820ca1',
                    'clientSecret' => '4f7d3256219b30105d23b0ce8bfa0b0ecf5966aa',
                ],
                'twitter' => [
                      'class' => 'Da\User\AuthClient\Twitter',
                      'attributeParams' => [    
                           'include_email' => 'true',   
                       ],
                        'consumerKey' => 'bzimDgz1OSL4vFzY9dl4Lxxro',
                        'consumerSecret' => 'xzAyOPykzsEny8LFNt833nHmy4eCX30vt2E00l6SOafijkBmwM',
                        //'returnUrl' => 'http://figmadaemon.com/defining2/frontend/web/index.php?r=user/security/auth&authclient=twitter',
                ],
                /*'linkedin' => [
                    'class' => 'Da\User\AuthClient\Linkedin',
                    'clientId' => 'linkedin_client_id',
                    'clientSecret' => 'linkedin_client_secret',
                ]*/
            ]
        ],
        'socialShare' => [
            'class' => \ymaker\social\share\configurators\Configurator::class,
            'enableIcons' => true,
            'socialNetworks' => [
                'facebook' => [
                    'class' => \ymaker\social\share\drivers\Facebook::class,
                ],
                'twitter' => [
                    'class' => \ymaker\social\share\drivers\Twitter::class,
                ],
                'linkedin' => [
                    'class' => \ymaker\social\share\drivers\LinkedIn::class,
                ],
                'gmail' => [
                    'class' => \ymaker\social\share\drivers\Gmail::class,
                ],
            ],
        ],

    ],
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // other module settings
        ],
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
