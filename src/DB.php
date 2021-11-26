<?php
declare(strict_types=1);

namespace Devcompru\Users;

use Devcompru\DB\Connection;
use PDO;
use PDOStatement;


class DB
{
    private PDO $pdo;
    private PDOStatement $statement;

    private array $_config;

    /**
     * @param array $config
     * @param $hostname
     * @param $database
     * @param $charset
     * @param $username
     * @param $password
     */

    function __construct(array $config)
    {
        $this->_config = $config;

        $this->connect();
    }

    public function connect(): DB
    {
        $dsn = "mysql:host={$this->_config['hostname']};dbname={$this->_config['database']};charset={$this->_config['charset']}";
        //MOX($dsn);
        $opt = [
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+03:00'"
        ];

        try {
            $this->pdo = new PDO($dsn, $this->_config['username'], $this->_config['password'], $opt);

        }
        catch (PDOException $e) {

            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $this;

    }

    public function query($sql, $params = []): PDOStatement
    {
        $this->statement = $this->pdo->prepare($sql);

        $this->statement->execute($params);

        return $this->statement;
    }




}