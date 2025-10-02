<?php
/**
 * Punto de entrada principal de la aplicación El Faro
 * 
 * Este archivo es el punto de entrada de la aplicación MVC.
 * Se encarga de cargar la configuración, helpers y despachar las rutas.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Configuración para producción
error_reporting(0);
ini_set('display_errors', '0');
session_start();

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar configuración de la aplicación
$config = require_once __DIR__ . '/../config/app.php';

// Cargar helpers
require_once __DIR__ . '/../app/Helpers/helpers.php';

// Cargar clases del Core
require_once __DIR__ . '/../app/Core/Router.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/View.php';

// Cargar controladores
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/ArticuloController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/ContactoController.php';
require_once __DIR__ . '/../app/Controllers/PerfilController.php';
require_once __DIR__ . '/../app/Controllers/ErrorController.php';

// Crear instancia del router
$router = new Router();

// Registrar rutas básicas
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

// Rutas de artículos
$router->get('/articulos', 'ArticuloController@index');
$router->get('/articulos/{id}', 'ArticuloController@show');
$router->get('/articulos/categoria/{categoria}', 'ArticuloController@categoria');
$router->get('/articulos/buscar', 'ArticuloController@search');

// Rutas de autenticación
$router->get('/registro', 'AuthController@registro');
$router->post('/registro', 'AuthController@procesarRegistro');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@procesarLogin');
$router->get('/logout', 'AuthController@logout');

// Rutas de contacto
$router->get('/contacto', 'ContactoController@index');
$router->post('/contacto', 'ContactoController@enviar');

// Rutas de perfil
$router->get('/perfil', 'PerfilController@index');
$router->get('/perfil/activar-suscripcion', 'PerfilController@activarSuscripcion');
$router->post('/perfil/activar-suscripcion', 'PerfilController@activarSuscripcion');
$router->post('/perfil/desactivar-suscripcion', 'PerfilController@desactivarSuscripcion');
$router->post('/perfil/actualizar', 'PerfilController@actualizar');

// Despachar la ruta actual
try {
    $router->dispatch();
} catch (Exception $e) {
    // En caso de error, mostrar página de error
    http_response_code(500);
    echo '<h1>Error del Servidor</h1>';
    echo '<p>Ha ocurrido un error inesperado. Por favor, inténtalo de nuevo más tarde.</p>';
    
    // En modo desarrollo, mostrar detalles del error
    if (isset($config['environment']) && $config['environment'] === 'development') {
        echo '<h2>Detalles del Error:</h2>';
        echo '<pre>' . $e->getMessage() . '</pre>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    }
}
