<?php
/**
 * Archivo de configuración principal.
 * Aquí definimos parámetros de conexión a la base de datos
 * y otros ajustes de la aplicación.
 */

return [
    'db' => [
        'dsn'  => 'mysql:host=127.0.0.1;port=3306;dbname=hieribal;charset=utf8mb4',
        'user' => 'root',
        'pass' => '',
    ],
    'app' => [
    'base_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http')
                . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
                . '/Hieribal2/public',
],

    'google' => [
        'client_id'     => '78844207545-tf7tbie9fejdt2k6pericg7hvmemhgur.apps.googleusercontent.com',
        'client_secret' => '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', 
        'redirect_uri'  => 'http://localhost/Hieribal2/public/?r=google_callback',
    ],

     'mail' => [
        'host'       => 'smtp.gmail.com',
        'port'       => 587,
        'secure'     => 'tls', // o 'ssl' si usas el puerto 465
        'username'   => 'gustavoalexiscuevas@gmail.com', // tu gmail
        'password'   => 'bhgn jeju ajnu vhtm',           // tu app password
        'from_email' => 'gustavoalexiscuevas@gmail.com', // mismo que username
        'from_name'  => 'Hieribal',
    ],

];
