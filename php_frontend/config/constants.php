<?php
// API Configuration
define('DJANGO_API_URL', 'http://localhost:8000/api/plants/');
define('DJANGO_MEDIA_URL', 'http://localhost:8000/media/');

// Site Configuration
define('SITE_NAME', 'Plant Information System');
define('BASE_URL', 'http://localhost/php_frontend/');

// Hardcoded user credentials (username => hashed_password)
// Generate hashed passwords with: echo password_hash('yourpassword', PASSWORD_BCRYPT);
define('VALID_USERS', [
    'admin' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // password: "password"
]);
?>