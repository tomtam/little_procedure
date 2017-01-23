<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nWKcHbXShs_yyYIeF4Twr9QyS3PAD-Qy',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class'  => 'Swift_SmtpTransport',
                'host'   => 'smtp.exmail.qq.com',
                'username' => 'ehomepay-service@ehomepay.com.cn',
                'password' => 'h0me#link$',
                'port'  => '25',
                'encryption' => 'tls'
            ],
            'messageConfig' => [
                'charset' => 'utf-8',
                'from' => ['ehomepay-service@ehomepay.com.cn' => 'ehomepay-service']
            ],
        ],
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
