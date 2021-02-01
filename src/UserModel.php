<?php
declare(strict_types=1);


namespace Devcompru\Users;


class UserModel
{
    public int $id=0;
    public string $GUID;
    public string $access_key;
    public string $username='';
    public string $pass_hash='';


    public string $reg_user_ip='';
    public string $oauth_token='';
    public string $oauth_id='';
    public string $oauth_type='';
    public int    $ban=0;

    public string $password_reset='';
    public int    $created_at;
    public int    $updated_at;

    private string $table_name = 'users';


    public function setTableName(string $table_name): void
    {
        $this->table_name = $table_name;
    }

    public  function createTableSQL()
    {
        $SQL = <<<SQL
CREATE TABLE {$this->table_name}
(
    id INT(255) AUTO_INCREMENT PRIMARY KEY,
    GUID VARCHAR(128) NOT NULL,
    access_key VARCHAR (128),
    username VARCHAR (128) NOT NULL,
    pass_hash VARCHAR (128) NOT NULL,
    reg_user_ip VARCHAR (20) NOT NULL,
    oauth_token VARCHAR (255) ,
    oauth_id VARCHAR (128),
    oauth_type VARCHAR (20),
    ban INT(1) DEFAULT 0,
    password_reset VARCHAR (128),
    created_at INT(32) NOT NULL,
    updated_at INT(32)
    
)
SQL;

        return $SQL;
    }



}