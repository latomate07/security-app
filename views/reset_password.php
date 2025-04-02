<?php
ob_start();
$authMiddleware = new \App\Middleware\AuthMiddleware();
$authMiddleware->requireGuest();

// Check if token and email are provided
if (!isset($_GET['token']) || !isset($_GET['email'])) {
    header('Location: /forgot-password?error="Lien de réinitialisation invalide"');
    exit;
}

$token = $_GET['token'];
$email = $_GET['email'];

// Validate token
$resetModel = new \App\Models\PasswordReset();
if (!$resetModel->isTokenValid($token, $email)) {
    header('Location: /forgot-password?error="Jeton invalide ou expiré"');
    exit;
}

$title = "Réinitialiser le mot de passe - Security App";
require_once __DIR__ . '/layout/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new \App\Controllers\AuthController();
    $result = $authController->resetPassword($token, $email, $_POST['password']);
    
    if ($result['success']) {
        header('Location: /login?message=' . urlencode($result['message']));
        exit;
    } else {
        $error = $result['message'];
    }
}
?>

<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Réinitialiser le mot de passe</h1>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" required minlength="8"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-gray-600 text-xs mt-1">Le mot de passe doit comporter au moins 8 caractères</p>
            </div>
            
            <div class="mb-6">
                <label for="password_confirm" class="block text-gray-700 text-sm font-bold mb-2">Confirmer le nouveau mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="8"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Réinitialiser le mot de passe
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Client-side password confirmation validation
document.querySelector('form').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirm').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Les mots de passe ne correspondent pas!');
    }
});
</script>

<?php require_once __DIR__ . '/layout/footer.php'; ?>