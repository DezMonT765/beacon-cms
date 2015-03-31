<?php
return [
    'components' => [
        'db'=>[
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=beacon',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ]
    ],
];
