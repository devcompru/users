<?php
declare(strict_types=1);


namespace Devcompru\Users;

use \PDO;
use \PDOStatement;
use \PDOException;


class Database implements DatabaseInterface
{
    private PDO $pdo;
    private PDOStatement $statement;
    private array $config=[];
    public function __construct(array $config)
    {
        new ErrorHandler();
        if(
            isset($config['host'])
            && isset($config['db'])
            && isset($config['user'])
            && isset($config['password'])
        )
        {
            $this->config = $config;
        }
        else
        {
            throw new \Exception('Invalid database configuration');
        }



    }

    public function connect(): void
    {
        if(!isset($this->config['charset']))
            $this->config['charset'] = 'utf8';
        $dsn = "mysql:host={$this->config['host']};dbname={$this->config['db']};charset={$this->config['charset']}";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->config['user'], $this->config['password'], $opt);
        }
        catch (PDOException $e)
        {
            throw new \Exception('Server error', 500);
        }

    }

    public function disconnect(): void
    {
        // TODO: Implement disconnect() method.
    }

    public function query(string $sql, array $bindParams = []): mixed
    {
        try {
            $this->statement = $this->pdo->prepare($sql);
            $data = $this->statement->execute();
        }
        catch (PDOException $e){
            throw new \Exception('Server error', 500);
        }




        return $data;
    }
    public function execute(string $SQL)
    {
        $this->statement = $this->pdo->query($SQL);
        echo "<pre>";
        print_r($this->statement);
    }
}