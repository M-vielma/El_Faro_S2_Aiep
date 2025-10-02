<?php
// server.php (RAÍZ del repo)
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/public' . $path;

// Si piden un archivo real dentro de /public, lo servimos directo
if ($path !== '/' && file_exists($file) && is_file($file)) {
    return false; // el servidor embebido lo sirve
}

// Si no existe archivo real, pasamos todo a public/index.php (front controller)
require __DIR__ . '/public/index.php';