<?php
declare(strict_types=1);


namespace Devcompru\Users;


use Devcompru\Users\Registration;


class User
{
    private DatabaseInterface $database;
    private string $table_name = 'users';

    public UserModel $user_model;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;


    }


    public function registration():UserModel
    {

        return new UserModel();
    }





    /**
     * Default SQL query and action create table
     */
    public function setTableName(string $table_name): void
    {
        $this->table_name = $table_name;
    }

    /**
     * get table scheme from file "users.sql'
     */
    public  function createTableSQL(): string
    {
        $SQL = str_replace('{table_name}', $this->table_name, file_get_contents(__DIR__.'/users.sql'));


        return $SQL;
    }
    public function createTable(string $SQL = null)
    {
        if($SQL === null)
            $SQL = $this->createTableSQL();

        $this->database->execute($SQL);
        return true;
    }

}