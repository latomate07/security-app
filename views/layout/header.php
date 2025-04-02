<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Security App' ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-blue-600">Security App</a>
            <div class="space-x-4">
                <?php if (\App\Utils\Auth::isLoggedIn()): ?>
                    <a href="/dashboard" class="text-gray-600 hover:text-blue-600">Tableau de bord</a>
                    <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded">Déconnexion</a>
                <?php else: ?>
                    <a href="/login" class="text-gray-600 hover:text-blue-600">Connexion</a>
                    <a href="/register" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto p-4">
        <?php if (isset($_GET['message'])): ?>
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 my-4" role="alert">
                <p><?= htmlspecialchars($_GET['message']) ?></p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-4" role="alert">
                <p><?= htmlspecialchars($_GET['error']) ?></p>
            </div>
        <?php endif; ?>