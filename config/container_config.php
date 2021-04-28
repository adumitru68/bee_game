<?php
return [

    'databaseHost' => 'localhost',
    'databasePort' => 3306,
    'databaseName' => 'bee_db',
    'databaseUser' => 'guser',
    'databasePass' => '1234',
    'pdoOptions' => [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_EMULATE_PREPARES => true,
    ],
    'pdoExecCommands' => [
        "SET time_zone='+00:00'"
    ],

    'swarmConfig' => [
        \App\Config\SwarmConfig::BEE_QUEEN => [
            'healthyPoints' => 100,
            'damage' => 8,
            'count' => 1,
        ],
        \App\Config\SwarmConfig::BEE_WORKER => [
            'healthyPoints' => 75,
            'damage' => 10,
            'count' => 5,
        ],
        \App\Config\SwarmConfig::BEE_DRONE => [
            'healthyPoints' => 50,
            'damage' => 12,
            'count' => 8,
        ]
    ],

    'templatePath' => __DIR__ . '/../template',

];