<?php
declare(strict_types=1);

namespace Devcompru\Users;

use Devcompru\Users\DTO\AuthDTO;
use Devcompru\Users\DTO\UsersDTO;
use PDO;

class Authorization
{
    private string $tablename = '';
    private string $table_auth = '';
    private string $cookies_name = 'XHFR';

    public function __construct(private $sql, private DetectMethod $method, private $config)
    {
        $this->tablename = $this->config['table_users'];
        $this->table_auth = $this->config['table_auth'];

    }


    public function init()
    {
        if($login = $this->authBySession())
        {

        }
        elseif($login = $this->authByCookies())
        {
            return   $login;
        }
        elseif($login = $this->authByToken())
        {

        }
        elseif($login = $this->authByUsernamePassword())
        {
            return   $this->login($login);
        }
        else
        {
            return false;
        }




    }


    private function authBySession()
    {


    }
    private function authByCookies()
    {
        if(isset($_COOKIE[$this->cookies_name]))
        {
            $cookies = filter_var($_COOKIE[$this->cookies_name], FILTER_SANITIZE_STRING);
            $db = new DB($this->sql);
            $sql = "SELECT * from {$this->table_auth} WHERE cookies = :cookies";
            $params = ['cookies'=>$cookies];
            $query = $db->query($sql, $params);
            $query->setFetchMode(PDO::FETCH_CLASS, AuthDTO::class);
            $result = $query->fetch();
            $sql = "UPDATE {$this->table_auth} SET count = :count";
            $params = ['count'=>$result->count+1];
            $query = $db->query($sql, $params);
            return $result->guid;
        }

        return false;


    }
    private function authByToken()
    {


    }
    private function authByUsernamePassword()
    {
        $db = new DB($this->sql);
        $sql = "SELECT * from {$this->tablename} WHERE username = :username";
        $params = ['username'=>$this->method->username];

        $query = $db->query($sql, $params);
        $query->setFetchMode(PDO::FETCH_CLASS, UsersDTO::class);
        $result = $query->fetch();

        if($result && $this->method->validatePassword($result->password))
        {
            return $result;
        }
        else
        {
            return false;
        }

    }


    private function login(UsersDTO $user, $cookie=false)
    {

        IF(!$cookie)
        {
            $auth = new AuthDTO();
            $auth->guid = $user->guid;
            $auth->ip = getIP();
            $auth->agent = getUserAgent();

            $db = new DB($this->sql);
            $sql = "SELECT * from {$this->table_auth} WHERE guid = :guid AND ip = :ip AND agent = :agent";
            $params = ['guid'=>$user->guid, 'ip'=>$auth->ip, 'agent'=>$auth->agent];
            $query = $db->query($sql, $params);
            $query->setFetchMode(PDO::FETCH_CLASS, AuthDTO::class);
            $result = $query->fetch();

            if(!$result)
            {

                $sql = "INSERT INTO {$this->table_auth} (guid, ip, agent) VALUES (:guid, :ip, :agent)";
                $params = ['guid'=>$user->guid, 'ip'=>$auth->ip, 'agent'=>$auth->agent];
                $query = $db->query($sql, $params)->rowCount();
                $sql = "SELECT * from {$this->table_auth} WHERE guid = :guid AND ip = :ip AND agent = :agent";
                $params = ['guid'=>$user->guid, 'ip'=>$auth->ip, 'agent'=>$auth->agent];
                $query = $db->query($sql, $params);
                $query->setFetchMode(PDO::FETCH_CLASS, AuthDTO::class);
                $result = $query->fetch();
                $this->setCookies($this->cookies_name, $result->cookies);
                return $result->guid;
            }
            else
            {
                $this->setCookies($this->cookies_name, $result->cookies);
                return $result->guid;
            }
        }
        ELSE
        {


        }





        // $this->setCookies($user);

      //  MOX($result);

    }

    public function setCookies($name, $value, $delete = false)
    {
        if($delete)
            $expired = time() - (60 * 60 * 24 * 30);
        else
            $expired = time() + (60 * 60 * 24 * 30);

        $cookie = setcookie($name,$value,$expired, '/', 'startup-clan.ru', true);
    }

    public function logout($guid)
    {
        $cookies = (isset($_COOKIE[$this->cookies_name]))?$_COOKIE[$this->cookies_name]:false;

        if($cookies)
        {
            $db = new DB($this->sql);
            $sql = "DELETE from {$this->table_auth} WHERE guid = :guid AND cookies = :cookies";
            $params = ['guid'=>$guid, 'cookies'=>$cookies];
            $query = $db->query($sql, $params);
            $query->setFetchMode(PDO::FETCH_CLASS, AuthDTO::class);
            $result = $query->fetch();
            $this->setCookies($this->cookies_name, '111', true);
        }
        
        return true;
    }

}