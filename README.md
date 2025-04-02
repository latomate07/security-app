# Security App

Ce projet est une application de sécurité qui implémente diverses fonctionnalités de sécurité pour les utilisateurs.

## Fonctionnalités

- **Page de connexion** : Authentification utilisateur avec nom d'utilisateur et mot de passe.
- **Réinitialisation de mot de passe** : Système permettant de réinitialiser le mot de passe en demandant l'email de l'utilisateur.
- **Vérification OTP** : Après l'inscription, un code OTP (6 chiffres) est requis pour vérifier l'identité de l'utilisateur.
- **Déconnexion** : Option pour se déconnecter après s'être connecté.
- **Journalisation** : Système de journalisation des activités utilisateur.

## Prérequis

- PHP >= 8.0
- Docker et Docker Compose
- Composer

## Installation

1. **Cloner le dépôt** :

    ```bash
    git clone https://github.com/latomate07/security-app.git
    cd votre-depot
    ```

2. **Installer les dépendances PHP** :

    ```bash
    composer install
    ```

3. **Configurer les variables d'environnement** :

    Créez un fichier `.env` à la racine du projet et ajoutez les variables d'environnement nécessaires :

    ```
    MAIL_USERNAME=votre_mail_username
    MAIL_PASSWORD=votre_mail_password
    MAIL_HOST=votre_mail_host
    MAIL_PORT=votre_mail_port
    DB_HOST=db
    DB_NAME=security_app
    DB_USER=user
    DB_PASSWORD=password
    ```

4. **Lancer les conteneurs Docker** :

    ```bash
    docker-compose up --build
    ```

5. **Accéder à PHPMyAdmin**

    Ouvrez votre navigateur et accédew à `http://localhost:8080` pour voir le tableau de bord PHPMyAdmin. <br/>
    Puis importez le fichier sql situé dans **`database/`**

6. **Accéder à l'application** :

    Ouvrez votre navigateur et accédez à `http://localhost:8000` pour voir l'application en cours d'exécution.