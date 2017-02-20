<?php
return [ 
		'vendorPath' => dirname ( dirname ( __DIR__ ) ) . '/vendor',
		'components' => [ 
				'mailer' => [ 
						'class' => 'yii\swiftmailer\Mailer',
						'viewPath' => '@common/mail',
						'useFileTransport' => true 
				],
		        'session' => [
		            'timeout' => 3600 * 24 * 30,
		        ],
		] 
];
