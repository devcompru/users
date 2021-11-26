<?php
declare(strict_types=1);

namespace Devcompru\Users;

use Devcompru\Users\DTO\UsersDTO;
use Exception;

class Users
{

    public static string  $table_users  = 'users_users',
                          $table_auth   = 'users_auth',
                          $table_logs   = 'users_log',
                          $session_name = 'authorization';


    private static ?Users $_instance = null;
    private ?array $config;
    private array $sql;
    private DetectMethod $method;
    public int $login = 0;
    private $db = null;

    public $guid = false;

    public function __construct(array $sql = [], array $config = null)
    {


        $this->config = $config;

       if(empty($sql))
           $sql = require __DIR__.'/'.'config.php';

        if(isset($sql['hostname'])  &&isset($sql['database']) && isset($sql['username'])
            && isset($sql['password']) && isset($sql['charset']))
        {
            $this->sql = $sql;
        }
        else
        {
            new Exception('Ошибка загрузки конфигурации базы данных', 500);
        }

       $this->init();

    }

    public  function init()
    {
        $this->method = new DetectMethod();

      //  $this->authorization();

    }

    public function authorization()
    {

        $result = (new Authorization($this->sql, $this->method, $this->config))->init();


        $this->guid = $result;

    }

    public function registartion(UsersDTO $user)
    {
        $this->db = new DB($this->sql);
        $registration = (new Registration($this->db, $user, $this->config))->run();
        return $registration;

    }

    public function logout()
    {
        $registration = (new Authorization($this->sql, $this->method, $this->config))->logout($this->guid);

    }


}