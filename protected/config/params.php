<?php
return [
	'adminEmail' => 'admin@example.com',

	// MODULES
	// Folder apa saja yg akan di muat secara otomatis oleh module auto loader.
	'moduleAutoloadPaths'    => ['@vendor/ommu', '@app/modules'],
	'moduleMarketplacePath'  => '@app/modules',
	'dontLoadModule'         => [],

	// THEMES
	// Letak folder tema, pada path ini tema akan diupload.
	'themePath' => '@themes',
	// Tema default yang akan digunakan jika tidak ada pengaturan yang dilakukan.
	'defaultTheme' => 'gentelella',

	// BACKOFFICE
	// Backoffice setting/option
	'backofficeOption' => array(
		'noindex' => true,
	),

	// RBAC
	// Jika diset 1 maka url akan dicatat pada file log
	'debugRBAC' => 0,
	// konfigurasi untuk menu admin, dapat dikelola melalui RBAC manager.
	'mdm.admin.configs' => [
		'menuTable' => 'ommu_core_menus',
	],
	'rbacDontLoadController' => ['default','user'],

	// GRIDVIEW
	// Gridview setting
	'gridView' => array(
		'JuiDatepicker' => false,
	),
];
