<?php

return array(
    'db' => array(
        'driver'         => 'Pdo_Mysql',
        'username'       => 'root',
        'password'       => 'sample',
        'port'           => 3306,
        'database'       => 'sample_test',
        'hostname'       => 'db',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
);
