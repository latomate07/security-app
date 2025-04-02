<?php
namespace App\Models;

use App\Utils\Database;
use App\Utils\Auth;

/**
 * PasswordReset model
 * Handles password reset operations
 */
class PasswordReset 
{
    private $db;
    
    public function __construct() 
    {
        $this->db = Database::getInstance();
    }
    
    public function create($email) 
    {
        // Delete any existing tokens for this email
        $this->deleteByEmail($email);
        
        // Create a new token
        $token = Auth::generateToken();
        
        $sql = "INSERT INTO password_resets (email, token) VALUES (?, ?)";
        $this->db->query($sql, [$email, $token]);
        
        return $token;
    }
    
    public function findByToken($token) 
    {
        $sql = "SELECT * FROM password_resets WHERE token = ? LIMIT 1";
        $result = $this->db->query($sql, [$token])->fetch();
        
        return $result ?: null;
    }
    
    public function deleteByEmail($email) 
    {
        $sql = "DELETE FROM password_resets WHERE email = ?";
        $this->db->query($sql, [$email]);
    }
    
    public function deleteByToken($token) 
    {
        $sql = "DELETE FROM password_resets WHERE token = ?";
        $this->db->query($sql, [$token]);
    }
    
    public function isTokenValid($token, $email, $expiryHours = 24) 
    {
        $reset = $this->findByToken($token);
        
        if (!$reset) {
            return false;
        }
        
        // Check if token belongs to the correct email
        if ($reset['email'] !== $email) {
            return false;
        }
        
        // Check if token is expired
        $createdAt = strtotime($reset['created_at']);
        $expiryTime = $createdAt + ($expiryHours * 3600);
        
        return time() <= $expiryTime;
    }
}