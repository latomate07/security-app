<?php
namespace App\Utils;

/**
 * Logger utility class
 * Handles system logging operations
 */
class Logger 
{
    private $db;
    
    public function __construct() 
    {
        $this->db = Database::getInstance();
    }
    
    public function log($action, $description = null, $userId = null) 
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        $sql = "INSERT INTO logs (user_id, action, description, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)";
                
        $this->db->query($sql, [$userId, $action, $description, $ip, $userAgent]);
    }
    
    public function getUserLogs($userId) 
    {
        $sql = "SELECT * FROM logs WHERE user_id = ? ORDER BY created_at DESC";
        return $this->db->query($sql, [$userId])->fetchAll();
    }
    
    public function getRecentLogs($limit = 100) 
    {
        $sql = "SELECT logs.*, users.username 
                FROM logs 
                LEFT JOIN users ON logs.user_id = users.id 
                ORDER BY created_at DESC 
                LIMIT ?";
                
        return $this->db->query($sql, [$limit])->fetchAll();
    }
}