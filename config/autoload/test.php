<?php

return array(
    'db' => array(
        'driver'         => 'Pdo_Mysql',
        'database'       => 'sample_test',
        'hostname'       => 'db',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
);
