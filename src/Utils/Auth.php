<?php
namespace App\Utils;

/**
 * Authentication utility class
 * Handles user authentication and security
 */
class Auth 
{
    public static function hashPassword($password) 
    {
        // Use PHP's password_hash function with a strong algorithm
        return password_hash($password, PASSWORD_ARGON2ID);
    }
    
    public static function verifyPassword($password, $hash) 
    {
        return password_verify($password, $hash);
    }
    
    public static function generateToken($length = 32) 
    {
        return bin2hex(random_bytes($length));
    }
    
    public static function generateOTP() 
    {
        // Generate a 6-digit OTP
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    public static function isLoggedIn() 
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public static function getUserId() 
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    public static function login($userId) 
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['last_activity'] = time();
        
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
    }
    
    public static function logout() 
    {
        // Unset all session variables
        $_SESSION = [];
        
        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        // Destroy the session
        session_destroy();
    }
    
    public static function checkSessionActivity($maxIdleTime = 1800) // 30 minutes
    {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $maxIdleTime)) {
            self::logout();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }
}