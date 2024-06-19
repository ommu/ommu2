<?php return [
	'components' => [
		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'mail.ommu.id',
				'username' => 'noreply@ommu.id',
				'password' => '2jmFUcX8MXSR',
				'port' => '465',
				'encryption' => 'ssl',
			],
		],
	],
]; ?>
