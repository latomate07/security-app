<?php
$authMiddleware = new \App\Middleware\AuthMiddleware();
$authMiddleware->requireGuest();

$title = "Inscription - Security App";
require_once __DIR__ . '/layout/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new \App\Controllers\AuthController();
    $result = $authController->register($_POST['username'], $_POST['email'], $_POST['password']);
    
    if ($result['success']) {
        // Redirect to OTP verification
        header('Location: /verify-otp?message=Inscription réussie. Veuillez vérifier votre OTP.');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Inscription</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/register">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-gray-600 text-xs mt-1">Le mot de passe doit comporter au moins 8 caractères</p>
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    S'inscrire
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-gray-600 text-sm">
                    Vous avez déjà un compte ?
                    <a href="/login" class="text-blue-500 hover:text-blue-700">Se connecter</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>