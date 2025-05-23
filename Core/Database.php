<?php

namespace Core;

use PDO;
use PDOException;

class Database
{

    // Database Configuration
    protected $host = 'localhost';
    protected $port = '3306';
    protected $dbname = 'norte_cafe';
    protected $username = 'root';
    protected $password = '';

    // SQL Variable
    public $connection;
    public $statement;



    /**
     * Automatically initialize the database
     * connection
     */
    public function __construct()
    {
        $this->iniDB();
    }

    public function iniDB()
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}";
        
        try {

            $this->connection = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            return $this->connection;
        } catch (PDOException $e) {
            dd('Database conntection failed ' . $e->getMessage());
        }
    }

    public function query($query, $param = [], $paginate = false)
    {

        try {
            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($param);

            // $this->statement - is for pagination
            // $this - is only for normal usage, which returns the whole instance of this all, everything this has to over. DD to understand.
            return $paginate ? $this->statement : $this;
        } catch (PDOException $e) {
            dd('Query error ' . $e->getMessage());
        }
    }

    /**
     * Eager load / gets multiple record
    */
    public function get()
    {
        return $this->statement->fetchAll();
    }

    /**
     * Gets one record that matches the query
     */
    public function find()
    {
        return $this->statement->fetch();
    }
}
