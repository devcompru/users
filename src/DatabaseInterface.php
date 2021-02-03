<?php
declare(strict_types=1);


namespace Devcompru\Users;


interface DatabaseInterface
{

    public function __construct(array $config);

    public function connect():void;
    public function disconnect():void;

    public function query(string $sql, array $bindParams): mixed;
    public function execute(string $SQL);

}