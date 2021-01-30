<?php
declare(strict_types=1);


namespace Devcompru;


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
        
        SQL;

        return $SQL;
    }


}