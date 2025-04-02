<?php
$authMiddleware = new \App\Middleware\AuthMiddleware();
$authMiddleware->requireGuest();

$title = "Mot de passe oublié - Security App";
require_once __DIR__ . '/layout/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new \App\Controllers\AuthController();
    $result = $authController->forgotPassword($_POST['email']);
    
    if ($result['success']) {
        $message = $result['message'];
    } else {
        $error = $result['message'];
    }
}
?>

<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Mot de passe oublié</h1>
        
        <?php if (isset($message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?= htmlspecialchars($message) ?></p>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php else: ?>
            <p class="text-gray-600 mb-6">
                Saisissez votre e-mail ci-dessous et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>
        <?php endif; ?>
        
        <form method="POST" action="/forgot-password">
            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-between mb-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Envoyer le lien de réinitialisation
                </button>
            </div>
            
            <div class="text-center">
                <a href="/login" class="text-blue-500 hover:text-blue-700 text-sm">
                    Retour à la connexion
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>