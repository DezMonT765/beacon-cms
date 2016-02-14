<?php
require_once('../components/MainView.php');
require_once('../models/Languages.php');
use app\components\MainView;
use app\models\Languages;

$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    'id' => 'basic',
    'language' => 'en-US',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/index',
    'aliases' => [
        '@file_save_dir' => '@app/web/files/',
        '@file_view_url' => '/files/',
        '@beacon_save_dir' =>'@app/web/beacon_images/',
        '@beacon_view_url' => '/beacon_images',
        '@backend_beacon_view_dir' => '@app/web/beacon_images',
        '@frontend_beacon_view_dir' =>'@app/web/beacon_images'
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'class' => 'app\components\MainView',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
        ],
        'assetManager' => [
            'bundles' =>[
                \yii\web\JqueryAsset::className() => [
                    'js'=> [
                        "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
                        "http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"
                    ],
                    'jsOptions' =>
                    [
                        'position' => MainView::POS_HEAD,
                    ],
                ],
                \yii\bootstrap\BootstrapAsset::className() => [
                    'baseUrl' => '@web',
                    'basePath' => '@webroot',
//                    'css'=>['css/lumen.min.css'],
//                    'css' => ['https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css'],
                    'css' => ['css/bootstrap.min.css']
                ],
                \yii\bootstrap\BootstrapPluginAsset::className() =>[
                    'js' => ['https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'],
                ]
            ],
            'appendTimestamp' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:[\w|-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[\w|-]+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:[\w|-]+>/<action:\w+>' => '<controller>/<action>',
            ]
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::className(),
            'cache'=>'cache',
            'defaultRoles'=>['super_admin','admin', 'user','promo_user'],
        ],
        'apcCache' => [
            'class' => yii\caching\MemCache::className(),
        ],

        'mailer' => [
            'class' =>'\zyx\phpmailer\Mailer',
            'viewPath' => '@app/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'messageConfig'    => [
                'from' => ['noreply@beacons.com'=>'Beacon CMS'],
            ],

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


        'session' => [
            'class' => 'yii\web\DbSession'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'forceTranslation' => true
                ],
            ],
        ],
        'languagepicker' => [
            'class' => '\lajax\languagepicker\widgets\LanguagePicker',
            'languages' => function(){
                return Languages::getLanguageNames(true);
            }
        ]
    ],
    'params' => $params,
];


return $config;
