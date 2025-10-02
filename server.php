<?php
/**
 * Server script para Render
 * 
 * Este archivo maneja el routing para el servidor embebido de PHP
 * que usa Render, sirviendo archivos estáticos y redirigiendo al index.php
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/public' . $path;

if ($path !== '/' && file_exists($file) && is_file($file)) {
    return false; // sirve archivos estáticos de /public
}
require __DIR__ . '/public/index.php';
