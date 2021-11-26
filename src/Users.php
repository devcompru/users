<?php
declare(strict_types=1);

namespace Devcompru\Users;

use Exception;

class Users
{
    private static ?Users $_instance = null;
    private array $config;


    public function __construct($config)
    {
        if(isset($config['hostname'])  &&isset($config['database']) && isset($config['username'])
            && isset($config['password']) && isset($config['charset']))
        {
            $this->config = $config;
        }
        else
        {
            new Exception('Ошибка загрузки конфигурации базы данных', 500);
        }


    }

    public static function init(){
        static::$_instance ??= new static;
        return static::$_instance;
    }



}