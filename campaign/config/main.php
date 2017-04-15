<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-campaign',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    //---------------------------------------------
    'modules'  => [
        'api' => [
            'class' => 'campaign\modules\api\Module',
        ],
        'wap' => [
            'class' => 'campaign\modules\wap\Module',
        ],
    ],
    //---------------------------------------------
    'controllerNamespace' => 'campaign\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                'class' => 'common\components\FileTarget',
                'levels' => ['info'],
                'categories' => ['api'],
                'enableDatePrefix' => true,
                'logFile' => '@app/runtime/logs/api_info.log',
                'logVars' => [''],
                'maxFileSize' => 1024,
                'maxLogFiles' => 20,
                ],
                [
                'class' => 'common\components\FileTarget',
                'levels' => ['info'],
                'categories' => ['camp'],
                'enableDatePrefix' => true,
                'logFile' => '@app/runtime/logs/camp_info.log',
                'logVars' => [''],
                'maxFileSize' => 1024,
                'maxLogFiles' => 20,
                ],
                [
                'class' => 'common\components\FileTarget',
                'levels' => ['info'],
                'categories' => ['order'],
                'enableDatePrefix' => true,
                'logFile' => '@app/runtime/logs/order_info.log',
                'logVars' => [''],
                'maxFileSize' => 1024,
                'maxLogFiles' => 20,
                ],
                [
                'class' => 'common\components\FileTarget',
                'levels' => ['info'],
                'categories' => ['wx'],
                'enableDatePrefix' => true,
                'logFile' => '@app/runtime/logs/wx_info.log',
                'logVars' => [''],
                'maxFileSize' => 1024,
                'maxLogFiles' => 20,
                ],
                [
                'class' => 'common\components\FileTarget',
                'levels' => ['info'],
                'categories' => ['theme'],
                'enableDatePrefix' => true,
                'logFile' => '@app/runtime/logs/theme_info.log',
                'logVars' => [''],
                'maxFileSize' => 1024,
                'maxLogFiles' => 20,
                ],
                [
                'class' => 'common\components\FileTarget',
                'levels' => ['info'],
                'categories' => ['share'],
                'enableDatePrefix' => true,
                'logFile' => '@app/runtime/logs/share_info.log',
                'logVars' => [''],
                'maxFileSize' => 1024,
                'maxLogFiles' => 20,
                ],
                [
                    'class' => 'common\components\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['user'],
                    'enableDatePrefix' => true,
                    'logFile' => '@app/runtime/logs/user_info.log',
                    'logVars' => [''],
                    'maxFileSize' => 1024,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
        'errorHandler' => [
            //'errorAction' => 'site/error',
        ],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
			    'class' => 'yii\rest\UrlRule',
			    'controller' => 'campaign',
			],
		],
    ],
    'params' => $params,
];
