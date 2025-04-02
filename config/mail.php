<?php
/**
 * Mail configuration file
 * Configuration for email services
 */

return [
    'host' => getenv('MAIL_HOST') ?: 'smtp.mailtrap.io',
    'port' => getenv('MAIL_PORT') ?: 2525,
    'username' => getenv('MAIL_USERNAME') ?: '',
    'password' => getenv('MAIL_PASSWORD') ?: '',
    'encryption' => 'tls',
    'from_address' => 'security@example.com',
    'from_name' => 'Security App',
];