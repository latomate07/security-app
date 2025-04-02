<?php
namespace App\Utils;

/**
 * Database utility class
 * Handles database connection and operations
 */
class Database 
{
    private static $instance = null;
    private $connection;
    
    private function __construct() 
    {
        $config = require_once __DIR__ . '/../../config/database.php';
        
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        
        try {
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() 
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() 
    {
        return $this->connection;
    }
    
    public function query($sql, $params = []) 
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}