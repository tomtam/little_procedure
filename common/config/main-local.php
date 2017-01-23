<?php
return [ 
		'vendorPath' => dirname ( dirname ( __DIR__ ) ) . '/vendor',
		'components' => [ 
		    'db_camp' => [
		        'class' => 'yii\db\Connection',
		        'dsn' => 'mysql:host=127.0.0.1;dbname=campaign',
		        'username' => 'root',
		        'password' => '12345678',
		        'charset' => 'utf8',
		    ],
			'mailer' => [ 
					'class' => 'yii\swiftmailer\Mailer',
					'viewPath' => '@common/mail',
					'useFileTransport' => true 
			] 
		] 
];
