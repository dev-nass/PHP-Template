<?php

namespace Core;

use PDO;
use PDOException;

class Database
{

    private $host;
    private $port;
    private $db_name;
    private $username;
    private $password;

    public $connection;
    public $statement;


    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->db_name = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USERNAME'];
        $this->password = $_ENV['DB_PASSWORD'];

        $this->iniDB();
    }

    public function iniDB()
    {

        $dsn = "mysql:host=$this->host;dbname=$this->db_name";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            return $this->connection;

        } catch (PDOException $e) {
            dd('Database conntection failed ' . $e->getMessage());
        }

    }

    public function query($query, $param = [])
    {

        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($param);

            return $this;
        } catch (PDOException $e) {
            dd('Query error ' . $e->getMessage());
        }
    }

    /**
     * Gets one record that
     * matches the query
     */
    public function find()
    {
        return $this->statement->fetch();
    }

    /**
     * Gets multiple records
     */
    public function get()
    {
        return $this->statement->fetchAll();
    }


}