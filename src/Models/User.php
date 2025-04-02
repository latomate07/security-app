<?php
namespace App\Models;

use App\Utils\Database;
use App\Utils\Auth;

/**
 * User model
 * Handles user-related database operations
 */
class User 
{
    private $db;
    
    public function __construct() 
    {
        $this->db = Database::getInstance();
    }
    
    public function create($username, $email, $password) 
    {
        $hashedPassword = Auth::hashPassword($password);
        $otpSecret = Auth::generateOTP();
        
        $sql = "INSERT INTO users (username, email, password, otp_secret) 
                VALUES (?, ?, ?, ?)";
                
        $this->db->query($sql, [$username, $email, $hashedPassword, $otpSecret]);
        
        return [
            'id' => $this->db->getConnection()->lastInsertId(),
            'username' => $username,
            'email' => $email,
            'otp_secret' => $otpSecret
        ];
    }
    
    public function findByUsername($username) 
    {
        $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $result = $this->db->query($sql, [$username])->fetch();
        
        return $result ?: null;
    }
    
    public function findByEmail($email) 
    {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        $result = $this->db->query($sql, [$email])->fetch();
        
        return $result ?: null;
    }
    
    public function findById($id) 
    {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $result = $this->db->query($sql, [$id])->fetch();
        
        return $result ?: null;
    }
    
    public function updateOtpVerified($userId, $verified = true) 
    {
        $sql = "UPDATE users SET otp_verified = ? WHERE id = ?";
        $this->db->query($sql, [$verified ? 1 : 0, $userId]);
    }
    
    public function updatePassword($userId, $newPassword) 
    {
        $hashedPassword = Auth::hashPassword($newPassword);
        
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $this->db->query($sql, [$hashedPassword, $userId]);
    }
}