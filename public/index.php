<?php
/**
 * Main entry point
 * Routes requests to the appropriate controllers
 */

// Start session
session_start();
ob_start();

// Load autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Load Dotenv
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Set default route
$route = $_GET['route'] ?? 'home';

// Routes
switch ($route) {
    case 'home':
        require_once __DIR__ . '/../views/home.php';
        break;
    case 'login':
        require_once __DIR__ . '/../views/login.php';
        break;
    case 'register':
        require_once __DIR__ . '/../views/register.php';
        break;
    case 'forgot-password':
        require_once __DIR__ . '/../views/forgot_password.php';
        break;
    case 'reset-password':
        require_once __DIR__ . '/../views/reset_password.php';
        break;
    case 'verify-otp':
        require_once __DIR__ . '/../views/verify_otp.php';
        break;
    case 'dashboard':
        require_once __DIR__ . '/../views/dashboard.php';
        break;
    case 'logout':
        (new \App\Controllers\AuthController)->logout();
        header('Location: /login?message=Déconnexion réussie');
        break;
    default:
        // 404 - Page not found
        header('HTTP/1.0 404 Not Found');
        require_once __DIR__ . '/../views/404.php';
        break;
}