<?php
namespace App\Middleware;

use App\Utils\Auth;
use App\Utils\Logger;

/**
 * Authentication middleware
 * Handles authentication checks and redirects
 */
class AuthMiddleware 
{
    private $logger;
    
    public function __construct() 
    {
        $this->logger = new Logger();
    }
    
    public function requireAuth() 
    {
        // Check if user is logged in
        if (!Auth::isLoggedIn()) {
            $this->logger->log('AUTH_FAILURE', 'Unauthenticated access attempt');
            header('Location: /login');
            exit;
        }
        
        // Check session activity timeout
        if (!Auth::checkSessionActivity()) {
            $this->logger->log('SESSION_TIMEOUT', 'Session timed out due to inactivity');
            header('Location: /login?message=Votre session a expiré pour cause d\'inactivité');
            exit;
        }
        
        // Check if OTP is verified
        $userId = Auth::getUserId();
        $userModel = new \App\Models\User();
        $user = $userModel->findById($userId);
        
        if (!$user || !$user['otp_verified']) {
            $this->logger->log('OTP_REQUIRED', 'OTP verification required', $userId);
            if($_SERVER['REQUEST_URI'] !== '/verify-otp') {
                header('Location: /verify-otp');
                exit;
            }
        }
    }
    
    public function requireGuest() 
    {
        if (Auth::isLoggedIn()) {
            header('Location: /dashboard');
            exit;
        }
    }
}