<?php
$authMiddleware = new \App\Middleware\AuthMiddleware();
$authMiddleware->requireGuest();

$title = "Connexion - Security App";
require_once __DIR__ . '/layout/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new \App\Controllers\AuthController();
    $result = $authController->login($_POST['username'], $_POST['password']);
    
    if ($result['success']) {
        $user = $result['user'];
        
        // Check if OTP is verified
        if (!$user['otp_verified']) {
            // Redirect to OTP verification
            header('Location: /verify-otp');
            exit;
        }
        
        // Redirect to dashboard
        header('Location: /dashboard?message=Connexion réussie');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Connexion</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Se connecter
                </button>
                <a href="/forgot-password" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-700">
                    Mot de passe oublié ?
                </a>
            </div>
            
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    Vous n'avez pas de compte ?
                    <a href="/register" class="text-blue-500 hover:text-blue-700">S'inscrire</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>