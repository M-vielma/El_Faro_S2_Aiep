<?php
/**
 * Configuración de la aplicación El Faro
 * 
 * Este archivo contiene la configuración principal de la aplicación
 * incluyendo el nombre de la app, URL base y configuración de UI.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Configuración de la aplicación
return [
    // Nombre de la aplicación
    'app_name' => 'El Faro',
    
    // URL base de la aplicación (se detecta automáticamente)
    'base_url' => '',   // Se detectará automáticamente en runtime
    
    // Framework de UI utilizado
    'ui' => 'bootstrap',
    
    // Configuración de entorno
    'environment' => 'production',
    'debug' => false,
    
    // Configuración de sesión
    'session' => [
        'lifetime' => 7200, // 2 horas
        'secure' => false,  // Para desarrollo local
        'httponly' => true,
        'samesite' => 'Lax'
    ],
    
    // Configuración de base de datos (para futuras implementaciones)
    'database' => [
        'host' => 'localhost',
        'name' => 'elfaro_db',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4'
    ],
    
    // Configuración de rutas de la aplicación
    'app_routes' => [
        'default_controller' => 'HomeController',
        'default_action' => 'index',
        '404_controller' => 'ErrorController',
        '404_action' => 'notFound'
    ],
    
    // Configuración de vistas
    'views' => [
        'path' => __DIR__ . '/../views/',
        'layout' => 'layout/main.php',
        'cache' => false
    ],
    
    // Configuración de helpers
    'helpers' => [
        'path' => __DIR__ . '/../app/Helpers/helpers.php'
    ],
    
    // Configuración de assets
    'assets' => [
        'css_path' => '/assets/css/',
        'js_path' => '/assets/js/',
        'images_path' => '/assets/images/',
        'uploads_path' => '/assets/uploads/'
    ],
    
    // Configuración de rutas de assets
    'routes' => [
        'assets' => '/assets/',
        'css' => '/assets/css/',
        'js' => '/assets/js/',
        'images' => '/assets/images/'
    ]
];
