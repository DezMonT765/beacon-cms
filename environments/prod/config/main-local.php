<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'db'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=beacons',
            'username' => 'galina',
            'password' => 'Beacons01!',
            'charset' => 'utf8',
        ]
    ],
];

return $config;
