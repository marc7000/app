<?php


$common = [
    'id' => 'app',
    'language' => 'en',
    'basePath' => dirname(__DIR__),
    'vendorPath' => '@app/../vendor',
    'runtimePath' => '@app/../runtime',
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@admin-views' => '@app/modules/backend/views',
    ],
    'components' => [
        'assetManager' => [
            // Note: For using mounted volumes or shared folders
            'dirMode' => YII_ENV_PROD ? 0777 : null,
            'bundles' => getenv('APP_ASSET_USE_BUNDLED') ?
                require(__DIR__.'/assets-gen/prod.php') :
                [
                    // Note: if your asset bundle includes bootstrap, you can disable the default asset
                    #'yii\bootstrap\BootstrapAsset' => false,
                ],
            'basePath' => '@app/../web/assets',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => getenv('DATABASE_DSN'),
            'username' => getenv('DATABASE_USER'),
            'password' => getenv('DATABASE_PASSWORD'),
            'charset' => 'utf8',
            'tablePrefix' => getenv('DATABASE_TABLE_PREFIX'),
            'enableSchemaCache' => YII_ENV_PROD ? true : false,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en',
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => YII_DEBUG ? false : true,
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            //'viewPath'         => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => YII_ENV_PROD ? false : true,
        ],
        // Note: enable db sessions, if multiple containers are running
        #'session' => [
        #    'class' => 'yii\web\DbSession'
        #],
        'settings' => [
            'class' => 'pheme\settings\components\Settings',
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'enablePrettyUrl' => getenv('APP_PRETTY_URLS') ? true : false,
            'showScriptName' => getenv('YII_ENV_TEST') ? true : false,
            'enableDefaultLanguageUrlCode' => true,
            'baseUrl' => '/',
            'rules' => [
                'docs/<file:[a-zA-Z0-9_\-\./]+>' => 'docs',
                #'docs' => 'docs/default/index',
            ],
            'languages' => explode(',',getenv('APP_LANGUAGES')),
        ],
        'user' => [
            'class' => 'app\components\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/security/login'],
            'identityClass' => 'dektrium\user\models\User',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/dektrium/yii2-user/views/admin' => '@app/views/user/admin',
                    '@yii/gii/views/layouts' => '@admin-views/layouts',
                ],
            ],
        ],

    ],
    'modules' => [
        'backend' => [
            'class' => 'app\modules\backend\Module',
            'layout' => '@admin-views/layouts/main',
        ],
        'pages' => [
            'class' => 'dmstr\modules\pages\Module',
            'layout' => '@admin-views/layouts/main',
            'availableRoutes' => [
                '/site/index' => '/site/index',
            ],
        ],
        'prototype'    => [
            'class'  => 'app\modules\prototype\Module',
            'layout' => '@admin-views/layouts/main',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'layout' => '@app/views/layouts/container',
            'defaultRoute' => 'profile',
            'admins' => ['admin'],
            'enableFlashMessages' => false,
        ],
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
            'layout' => '@admin-views/layouts/box',
            'enableFlashMessages' => false,
        ],
        'settings' => [
            'class' => 'pheme\settings\Module',
            'layout' => '@admin-views/layouts/box',
            'accessRoles' => ['Admin'],
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'root' => '@app/views',
            'layout' => '@admin-views/layouts/box',
            'allowedIPs' => ['*'],
            'roles' => ['admin', 'translate-manager'],
        ],
        'treemanager' => [
            'class' => '\kartik\tree\Module',
            'layout' => '@admin-views/layouts/main',
            'treeViewSettings' => [
                'nodeView' => '@vendor/dmstr/yii2-pages-module/views/treeview/_form',
                'fontAwesome' => true,
            ],
        ],
    ],
    'params' => [
        'appName' => getenv('APP_NAME'),
        'adminEmail' => getenv('APP_ADMIN_EMAIL'),
        'supportEmail' => getenv('APP_SUPPORT_EMAIL'),
        'yii.migrations' => [
            '@yii/rbac/migrations',
            '@dektrium/user/migrations',
            '@vendor/lajax/yii2-translate-manager/migrations',
            '@vendor/pheme/yii2-settings/migrations',
            '@app/modules/prototype/migrations'
        ],
    ],

];

$web = [
    'components' => [
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        // Logging
        'log' => [
            'targets' => [
                // writes to php-fpm output stream
                [
                    'class' => 'codemix\streamlog\Target',
                    'url' => 'php://stdout',
                    'levels' => ['info', 'trace'],
                    'logVars' => [],
                    'enabled' => YII_DEBUG,
                ],
                [
                    'class' => 'codemix\streamlog\Target',
                    'url' => 'php://stderr',
                    'levels' => ['error', 'warning'],
                    'logVars' => [],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('APP_COOKIE_VALIDATION_KEY'),
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
        ],
    ],
];

$console = [
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'db' => 'dmstr\console\controllers\MysqlController',
        'migrate' => 'dmstr\console\controllers\MigrateController',
        'translate' => '\lajax\translatemanager\commands\TranslatemanagerController',
    ],
    'components' => [

    ],
];

$allowedIPs = [
    '127.0.0.1',
    '::1',
    '192.168.*',
    '172.17.*',
];

// detecting current application type based on `php_sapi_name()` since we've no application ready yet.
if (php_sapi_name() == 'cli') {
    // Console application
    $config = \yii\helpers\ArrayHelper::merge($common, $console);
} else {
    // Web application
    if (YII_ENV_DEV) {
        // configuration adjustments for web 'dev' environment
        $common['bootstrap'][] = 'debug';
        $common['modules']['debug'] = [
            'class' => 'yii\debug\Module',
            'allowedIPs' => $allowedIPs,
        ];
    }
    $config = \yii\helpers\ArrayHelper::merge($common, $web);
}

if (YII_ENV_DEV || YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => $allowedIPs,
    ];

    if (file_exists(__DIR__.'/giiant.php')) {
        // Local configuration, if available
        $giiant = require __DIR__.'/giiant.php';
        $config = \yii\helpers\ArrayHelper::merge($config, $giiant);
    }
}

if (file_exists(__DIR__.'/local.php')) {
    // Local configuration, if available
    $local = require __DIR__.'/local.php';
    $config = \yii\helpers\ArrayHelper::merge($config, $local);
}

return $config;
