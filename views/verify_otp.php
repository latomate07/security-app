<?php
$authMiddleware = new \App\Middleware\AuthMiddleware();
$authMiddleware->requireAuth();

$userId = \App\Utils\Auth::getUserId();

$title = "Vérification de l'OTP - Security App";
require_once __DIR__ . '/layout/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new \App\Controllers\AuthController();
    $result = $authController->verifyOtp($userId, $_POST['otp']);
    
    if ($result['success']) {
        // Redirect to dashboard
        header('Location: /dashboard?message=OTP vérifié avec succès');
        exit;
    } else {
        $error = $result['message'];
    }
}

// Get user information
$userModel = new \App\Models\User();
$user = $userModel->findById($userId);
?>

<div class="max-w-md mx-auto mt-10">
    <div class="bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">Vérifier l'OTP</h1>
        
        <p class="text-gray-600 mb-6 text-center">
            Veuillez saisir le code à 6 chiffres envoyé à votre adresse électronique: <strong><?= htmlspecialchars($user['email']) ?></strong>
        </p>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/verify-otp">
            <div class="mb-6">
                <label for="otp" class="block text-gray-700 text-sm font-bold mb-2">Code OTP</label>
                <input type="text" id="otp" name="otp" required pattern="[0-9]{6}" maxlength="6"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-center text-2xl leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-gray-600 text-xs mt-1">Saisir le code à 6 chiffres</p>
            </div>
            
            <div class="flex flex-col items-center justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full mb-4">
                    Vérifier
                </button>
                
                <a href="/logout" class="text-blue-500 hover:text-blue-700 text-sm">
                    Annuler et se déconnecter
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>