<?php
declare(strict_types=1);

return [
    'sql'=>[
        'hostname'=>getenv('MYSQL_HOSTNAME'),
        'database'=>getenv('MYSQL_DATABASE'),
        'username'=>getenv('MYSQL_USER'),
        'password'=>getenv('MYSQL_PASSWORD'),
        'charset'=>getenv('MYSQL_CHARSET')
    ]


];
