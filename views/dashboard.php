<?php
$authMiddleware = new \App\Middleware\AuthMiddleware();
$authMiddleware->requireAuth();

$userId = \App\Utils\Auth::getUserId();

// Get user information
$userModel = new \App\Models\User();
$user = $userModel->findById($userId);

// Get user logs
$logger = new \App\Utils\Logger();
$logs = $logger->getUserLogs($userId);

$title = "Tableau de bord - Security App";
require_once __DIR__ . '/layout/header.php';
?>

<div class="max-w-4xl mx-auto mt-6">
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-blue-600">Tableau de bord</h1>
            <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Déconnexion</a>
        </div>
        
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations sur l'utilisateur</h2>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Nom d'utilisateur</p>
                        <p class="font-medium"><?= htmlspecialchars($user['username']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Email</p>
                        <p class="font-medium"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Date d'inscription</p>
                        <p class="font-medium"><?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">OTP Vérifié</p>
                        <p class="font-medium">
                            <?php if ($user['otp_verified']): ?>
                                <span class="text-green-600">Oui</span>
                            <?php else: ?>
                                <span class="text-red-600">Non</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Activité récentes</h2>
            
            <?php if (empty($logs)): ?>
                <p class="text-gray-600 italic">Aucune activité trouvée.</p>
            <?php else: ?>
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse IP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Heure</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($log['action']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= htmlspecialchars($log['description'] ?: '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($log['ip_address'] ?: '-') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars(date('F j, Y, g:i a', strtotime($log['created_at']))) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>