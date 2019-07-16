<?php
return [
	'components' => [
		// uncomment the following to use a MySQL database
		'db' => [
			'class' => 'yii\db\Connection',
			'dsn' => 'mysql:host=db.host;dbname=dbname=db.name',
			'username' => 'db.username',
			'password' => 'db.password',
			'charset' => 'utf8',

			// Schema cache options (for production environment)
			//'enableSchemaCache' => true,
			//'schemaCacheDuration' => 60,
			//'schemaCache' => 'cache',
		],
	],
];