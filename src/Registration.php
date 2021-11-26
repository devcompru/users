<?php
declare(strict_types=1);

namespace Devcompru\Users;


use Devcompru\Users\DTO\UsersDTO;
use PDO;
use DateTime;

class Registration
{
    public bool $error = false;
    public string $message = '';
    public string $tablename = '';

    public function __construct(private DB $db, private UsersDTO $user, private array $config= [])
    {
        $this->tablename = Users::$table_users;
    }


    public function run()
    {
        if(!$this->checkFields())
            return $this;

        if(!$this->registration())
            return $this;




        return $this;

    }

    private  function registration()
    {
        $SQL = "SELECT * FROM {$this->tablename} WHERE reg_ip = :reg_ip";
        $params = ['reg_ip'=>getIP()];

        $exist = $this->db->query($SQL, $params)->fetch();
        if($exist){
            $date = new DateTime($exist['created_at']);
            $today = new DateTime();
            $diff = date_diff($date, $today)->h;

            if((int)$diff<3) {
                $this->error = true;
                $this->message = 'Повторная регистрация с данного IP возможно по истечению 3х часов';
                return false;
            }
        }

        if(!in_array('email', $this->config['required'])) {
            $SQL = "SELECT * FROM {$this->tablename} WHERE username = :username";
            $params = ['username'=>$this->user->username];
        }
        else
        {
            $SQL ="SELECT * FROM {$this->tablename} WHERE username = :username OR email = :email";
            $params = ['username'=>$this->user->username, 'email'=>$this->user->email];

        }





        $exist = $this->db->query($SQL, $params)->rowCount();

        if($exist > 0)
        {
            $this->error = true;
            $this->message = 'Данное имя недоступно для регистрации';

            return false;
        }

        else
        {
            if($this->createUser())
            {
                $result = $this->db->query($SQL, $params);
                $result->setFetchMode(PDO::FETCH_CLASS, $this->user::class);
                $this->user = $result->fetch();
                return $this->user;
            }
            else
            {
                $this->error = true;
                $this->message = 'Ошибка сервера. Попробуйте позже.';

                return false;
            }
        }

    }
    private function createUser()
    {
        $SQL = "INSERT INTO {$this->tablename} 
    (username, password, email, reg_ip) 
    VALUES (:username, :password, :email, :reg_ip)
    ";
        $params = [
            'username'=>$this->user->username,
            'password'=>password_hash($this->user->password, PASSWORD_DEFAULT),
            'email'=>$this->user->email,
            'reg_ip'=>$this->user->reg_ip,
        ];

     $result = $this->db->query($SQL, $params)->rowCount();

     return ($result==1)?true:false;

    }
    private function checkFields(): bool
    {
        foreach ($this->config['required'] as $field)
        {
            if(!$this->user->$field)
            {
                $this->error = true;
                $this->message .= "Поле $field обязательно для заполнения.";
            }
        }
        return !$this->error;
    }


}