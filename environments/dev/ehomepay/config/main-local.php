<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
    	    'transport' => [
    	        'class'  => 'Swift_SmtpTransport',
    	        'host'   => 'smtp.exmail.qq.com',
    	        'username' => 'ehomepay-service@ehomepay.com.cn',
    	        'password' => 'h0me#link$',
    	        'port'  => '25',
    	        'encryption' => 'ssl'
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
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
