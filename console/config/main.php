<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
					'logFile' => '@app/runtime/logs/app.log',
                    'logVars' => [''],
                ],
            	[
            		'class' => 'yii\log\FileTarget',
            		'levels' => ['info'],
            		'categories' => ['credit'],
            		'logFile' => '@app/runtime/logs/credit/credit_info_'.date('Y-m-d').'.log',
            		'logVars' => [''],
            		'maxFileSize' => 10240,
            		'maxLogFiles' => 20,
            	],
            ],
        ],
    ],
    'params' => $params,
];
