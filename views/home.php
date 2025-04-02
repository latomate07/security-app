<?php
// Home page
$title = "Accueil - Security App";
require_once __DIR__ . '/layout/header.php';
?>

<div class="text-center py-10">
    <h1 class="text-4xl font-bold text-blue-600 mb-4">Bienvenue !</h1>
    <p class="text-xl text-gray-600 mb-8">Une application sécurisée avec des fonctions d'authentification modernes</p>
    
    <div class="flex justify-center space-x-6">
        <?php if (!\App\Utils\Auth::isLoggedIn()): ?>
            <a href="/login" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg text-lg">Connexion</a>
            <a href="/register" class="bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg text-lg">Inscription</a>
        <?php else: ?>
            <a href="/dashboard" class="bg-blue-500 hover:bg-blue-600 text-white py-3 px-6 rounded-lg text-lg">Accéder au tableau de bord</a>
        <?php endif; ?>
    </div>
    
    <div class="mt-16 max-w-3xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Fonctionnalités</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Connexion sécurisée</h3>
                <p class="text-gray-600">Authentification de l'utilisateur avec hachage sécurisé du mot de passe et gestion de la session.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Vérification OTP</h3>
                <p class="text-gray-600">Authentification à deux facteurs avec mots de passe à usage unique.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-blue-600 mb-2">Reset Mot de passe</h3>
                <p class="text-gray-600">Processus sécurisé de récupération du mot de passe.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>