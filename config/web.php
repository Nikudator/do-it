<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$auth = require __DIR__ . '/auth.php';

$config = [
    'id' => 'basic',
    'name' => 'Заметки Одноглазого Джо',
    'basePath' => dirname(__DIR__),
    //'homeUrl' => ['post/index'],
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'post' => [
            'class' => 'app\modules\post\Module',
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ],
    ],

    
    'components' => [
        'request' => [
            'cookieValidationKey' => 'XE1AqtHubFKcEr_YF6FNgbtYgLHY0u1G',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login'],
            'authTimeout' => 60 * 60 * 24 * 7,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
        ],
        'db' => $db,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'post',
                'contact' => 'site/contact',
                'about' => 'site/about',
                'agreement' => 'site/agreement',
                'site/captcha' => 'site/captcha',
                '<_a:error>' => 'site/default/<_a>',
                '<_a:(login|logout|signup|email-confirm|password-request|password-reset|resend-verification-email|auth)>' => 'user/default/<_a>',
                '<controller:\w+>/<id:\d+>' => '<controller>/default/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/default/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/default/<action>',
            ],

        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['guest', 'user', 'redactor', 'moderator', 'admin', 'root'],
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.Y',
            'datetimeFormat' => 'dd.MM.Y H:i:s',
            'timeFormat' => 'H:i:s',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
        ],
        'authClientCollection' => $auth,
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/first'],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['95.129.147.100', '193.143.65.18', '193.143.65.55', '91.186.109.*', '91.186.108.*', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['95.129.147.100', '193.143.65.18', '193.143.65.55', '91.186.109.*', '91.186.108.*', '::1'],
    ];
}

return $config;
