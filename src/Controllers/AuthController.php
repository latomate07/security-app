<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use App\Utils\Auth;
use App\Utils\Mailer;
use App\Utils\Logger;

/**
 * Authentication controller
 * Handles login, registration, password reset, and OTP verification
 */
class AuthController 
{
    private $userModel;
    private $resetModel;
    private $mailer;
    private $logger;
    
    public function __construct() 
    {
        $this->userModel = new User();
        $this->resetModel = new PasswordReset();
        $this->mailer = new Mailer();
        $this->logger = new Logger();
    }
    
    public function register($username, $email, $password) 
    {
        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Tous les champs sont obligatoires'
            ];
        }
        
        // Check if username or email already exists
        if ($this->userModel->findByUsername($username)) {
            return [
                'success' => false,
                'message' => "Le nom d'utilisateur existe déjà"
            ];
        }
        
        if ($this->userModel->findByEmail($email)) {
            return [
                'success' => false,
                'message' => "L'e-mail existe déjà"
            ];
        }
        
        // Create the user
        $user = $this->userModel->create($username, $email, $password);
        
        // Send OTP email
        $otpSubject = "Votre code de vérification OTP";
        $otpBody = "Bonjour {$username},<br><br>
                    Votre code de vérification OTP est le suivant: <strong>{$user['otp_secret']}</strong><br><br>
                    Veuillez saisir ce code pour compléter votre inscription.<br><br>
                    Merci.";
                    
        $this->mailer->send($email, $otpSubject, $otpBody);
        
        // Log the registration
        $this->logger->log('REGISTER', 'User registered successfully', $user['id']);
        
        // Set session (user is logged in but needs OTP verification)
        Auth::login($user['id']);
        
        return [
            'success' => true,
            'message' => "Inscription réussie. Veuillez vérifier votre courrier électronique pour l'OTP",
            'user_id' => $user['id']
        ];
    }
    
    public function login($username, $password) 
    {
        // Validate input
        if (empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => "Un nom d'utilisateur et un mot de passe sont nécessaires"
            ];
        }
        
        // Find user by username
        $user = $this->userModel->findByUsername($username);
        
        // Check if user exists and password is correct
        if (!$user || !Auth::verifyPassword($password, $user['password'])) {
            $this->logger->log('LOGIN_FAILURE', 'Failed login attempt for username: ' . $username);
            
            return [
                'success' => false,
                'message' => "Informations d'identification invalides"
            ];
        }
        
        // Set session
        Auth::login($user['id']);
        
        // Log the login
        $this->logger->log('LOGIN', 'User logged in successfully', $user['id']);
        
        return [
            'success' => true,
            'message' => 'Login successful',
            'user' => $user
        ];
    }
    
    public function logout() 
    {
        $userId = Auth::getUserId();
        
        // Log the logout
        if ($userId) {
            $this->logger->log('LOGOUT', 'User logged out', $userId);
        }
        
        // Clear session
        Auth::logout();
        
        return [
            'success' => true,
            'message' => 'Déconnexion réussie'
        ];
    }
    
    public function verifyOtp($userId, $otp) 
    {
        // Validate input
        if (empty($userId) || empty($otp)) {
            return [
                'success' => false,
                'message' => "L'identifiant de l'utilisateur et l'OTP sont nécessaires"
            ];
        }
        
        // Find user
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            $this->logger->log('OTP_VERIFICATION', 'Invalid user ID for OTP verification');
            
            return [
                'success' => false,
                'message' => 'Utilisateur non valide'
            ];
        }
        
        // Check OTP
        if ($user['otp_secret'] !== $otp) {
            $this->logger->log('OTP_VERIFICATION', 'Invalid OTP entered', $userId);
            
            return [
                'success' => false,
                'message' => 'OTP non valide'
            ];
        }
        
        // Update user
        $this->userModel->updateOtpVerified($userId);
        
        // Log the verification
        $this->logger->log('OTP_VERIFICATION', 'OTP verified successfully', $userId);
        
        return [
            'success' => true,
            'message' => 'OTP vérifié avec succès'
        ];
    }
    
    public function forgotPassword($email) 
    {
        // Validate input
        if (empty($email)) {
            return [
                'success' => false,
                'message' => "L'email est requis"
            ];
        }
        
        // Find user by email
        $user = $this->userModel->findByEmail($email);
        
        // If user doesn't exist, still return success to prevent email enumeration
        if (!$user) {
            $this->logger->log('PASSWORD_RESET', 'Password reset requested for non-existent email: ' . $email);
            
            return [
                'success' => true,
                'message' => 'Si votre adresse e-mail existe dans notre système, vous recevrez un lien de réinitialisation de votre mot de passe.'
            ];
        }
        
        // Create password reset token
        $token = $this->resetModel->create($email);
        
        // Send password reset email
        $resetLink = "http://{$_SERVER['HTTP_HOST']}/reset-password?token={$token}&email={$email}";
        $resetSubject = "Demande de réinitialisation du mot de passe";
        $resetBody = "Bonjour {$user['username']},<br><br>
                      Vous avez demandé la réinitialisation de votre mot de passe. <br><br>
                      Veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe.:<br><br>
                      <a href='{$resetLink}'>{$resetLink}</a><br><br>
                      Ce lien expirera dans 24 heures.<br><br>
                      Si vous ne l'avez pas demandé, vous pouvez ignorer cet e-mail.";
                      
        $this->mailer->send($email, $resetSubject, $resetBody);
        
        // Log the request
        $this->logger->log('PASSWORD_RESET', 'Password reset requested', $user['id']);
        
        return [
            'success' => true,
            'message' => 'Si votre adresse e-mail existe dans notre système, vous recevrez un lien de réinitialisation de votre mot de passe.'
        ];
    }
    
    public function resetPassword($token, $email, $password) 
    {
        // Validate input
        if (empty($token) || empty($email) || empty($password)) {
            return [
                'success' => false,
                'message' => "Un jeton, un email et un nouveau mot de passe sont requis."
            ];
        }
        
        // Check if token is valid
        if (!$this->resetModel->isTokenValid($token, $email)) {
            $this->logger->log('PASSWORD_RESET', 'Invalid or expired password reset token');
            
            return [
                'success' => false,
                'message' => 'Jeton invalide ou expiré'
            ];
        }
        
        // Find user by email
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            $this->logger->log('PASSWORD_RESET', 'Password reset attempted for non-existent email: ' . $email);
            
            return [
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ];
        }
        
        // Update password
        $this->userModel->updatePassword($user['id'], $password);
        
        // Delete the token
        $this->resetModel->deleteByToken($token);
        
        // Log the password reset
        $this->logger->log('PASSWORD_RESET', 'Password reset successful', $user['id']);
        
        return [
            'success' => true,
            'message' => 'Réinitialisation du mot de passe réussie. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.'
        ];
    }
}