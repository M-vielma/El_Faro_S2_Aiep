<?php
/**
 * Funciones auxiliares para la aplicación El Faro
 * 
 * Este archivo contiene funciones de utilidad que son utilizadas
 * a lo largo de toda la aplicación para tareas comunes.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Cargar configuración de la aplicación
// Intentar cargar desde diferentes ubicaciones posibles
$config = null;

// Primera opción: desde app/Helpers
$configPath1 = __DIR__ . '/../../config/app.php';
if (file_exists($configPath1)) {
    $config = require $configPath1;
}

// Segunda opción: desde public/
$configPath2 = __DIR__ . '/../config/app.php';
if (!$config && file_exists($configPath2)) {
    $config = require $configPath2;
}

// Validar que la configuración se cargó correctamente
if (!is_array($config)) {
    $config = [
        'app_name' => 'El Faro',
        'base_url' => '/Elfaro_taller_s2/public/',
        'ui' => 'bootstrap',
        'assets' => [
            'css_path' => '/assets/css/',
            'js_path' => '/assets/js/',
            'images_path' => '/assets/images/',
            'uploads_path' => '/assets/uploads/'
        ],
        'routes' => [
            'assets' => '/assets/',
            'css' => '/assets/css/',
            'js' => '/assets/js/',
            'images' => '/assets/images/'
        ]
    ];
}

/**
 * Genera la URL base de la aplicación
 * 
 * Esta función construye la URL base completa de la aplicación
 * basándose en la configuración definida en config/app.php
 * 
 * @param string $path Ruta adicional a agregar a la URL base
 * @return string URL base completa
 */
function base_url($path = '') {
    global $config;
    
    // Obtener la URL base de la configuración
    $baseUrl = $config['base_url'] ?? '';
    
    // Si la base_url está vacía, detectarla automáticamente
    if (empty($baseUrl)) {
        $baseUrl = detect_base_url();
    }
    
    // Si no hay path, devolver la URL base sin la barra final
    if (empty($path)) {
        return rtrim($baseUrl, '/');
    }
    
    // Limpiar el path y construir la URL completa
    $path = ltrim($path, '/');
    return $baseUrl . $path;
}

/**
 * Genera una URL interna de la aplicación (para enlaces internos)
 * 
 * Esta función genera URLs absolutas para enlaces internos de la aplicación
 * (navegación, formularios, etc.) que funcionan en cualquier entorno
 * 
 * @param string $path Ruta adicional
 * @return string URL interna absoluta
 */
function app_url($path = '') {
    // Obtener el protocolo (http o https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    
    // Obtener el host
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Obtener el script name (ej: /Elfaro_taller_s2/public/index.php)
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Obtener la ruta del directorio del script (ej: /Elfaro_taller_s2/public)
    $scriptDir = dirname($scriptName);
    
    // Normalizar la ruta (convertir \ a / en Windows y eliminar barras dobles)
    $scriptDir = str_replace('\\', '/', $scriptDir);
    $scriptDir = str_replace('//', '/', $scriptDir);
    
    // Normalizar la ruta (eliminar barras al final)
    $basePath = rtrim($scriptDir, '/');
    
    // Construir la URL base
    $baseUrl = $protocol . '://' . $host . $basePath;
    
    // Si no hay path, devolver solo la URL base
    if (empty($path)) {
        return $baseUrl;
    }
    
    // Limpiar el path (eliminar barras al inicio y final)
    $path = trim($path, '/');
    
    // Si el path está vacío después de limpiar, devolver solo la URL base
    if (empty($path)) {
        return $baseUrl;
    }
    
    // Construir la URL completa
    return $baseUrl . '/' . $path;
}

/**
 * Detecta automáticamente la URL base del proyecto
 * 
 * Esta función detecta la ruta base del proyecto basándose en
 * las variables de servidor disponibles
 * 
 * @return string URL base detectada
 */
function detect_base_url() {
    // Obtener el protocolo (http o https)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    
    // Obtener el host
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Obtener el script name (ej: /Elfaro_taller_s2/public/index.php)
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Obtener la ruta del directorio del script
    $scriptDir = dirname($scriptName);
    
    // Si estamos en public/index.php, el base_url es el directorio padre
    // Si estamos en otro lugar, usar el scriptDir
    if (basename($scriptDir) === 'public') {
        // Estamos en public/, así que la base es el directorio padre
        $basePath = dirname($scriptDir);
    } else {
        // Usar el directorio del script actual
        $basePath = $scriptDir;
    }
    
    // Normalizar la ruta (eliminar barras dobles, etc.)
    $basePath = rtrim($basePath, '/');
    
    // Construir la URL completa
    return $protocol . '://' . $host . $basePath . '/';
}

/**
 * Genera la URL para assets (CSS, JS, imágenes)
 * 
 * Esta función construye URLs para recursos estáticos
 * basándose en la configuración de assets
 * 
 * @param string $asset Ruta del asset
 * @return string URL completa del asset
 */
function asset_url($asset) {
    global $config;
    
    // Limpiar la ruta del asset
    $asset = ltrim($asset, '/');
    
    // Validar configuración y usar valor por defecto si es necesario
    if (!is_array($config) || !isset($config['routes']['assets'])) {
        return base_url('assets/' . $asset);
    }
    
    // Construir la URL del asset
    return $config['routes']['assets'] . $asset;
}

/**
 * Genera la URL para archivos CSS
 * 
 * @param string $css Archivo CSS
 * @return string URL completa del CSS
 */
function css_url($css) {
    $css = ltrim($css, '/');
    return base_url('assets/css/' . $css);
}

/**
 * Genera la URL para archivos JavaScript
 * 
 * @param string $js Archivo JavaScript
 * @return string URL completa del JS
 */
function js_url($js) {
    $js = ltrim($js, '/');
    return base_url('assets/js/' . $js);
}

/**
 * Genera la URL para imágenes
 * 
 * @param string $image Archivo de imagen
 * @return string URL completa de la imagen
 */
function image_url($image) {
    $image = ltrim($image, '/');
    return base_url('assets/images/' . $image);
}

/**
 * Escapa caracteres especiales para prevenir XSS
 * 
 * Esta función escapa caracteres especiales en strings para
 * prevenir ataques de Cross-Site Scripting (XSS)
 * 
 * @param string $string String a escapar
 * @return string String escapado
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Genera un token CSRF para formularios
 * 
 * Esta función genera un token único para prevenir
 * ataques Cross-Site Request Forgery (CSRF)
 * 
 * @return string Token CSRF
 */
function csrf_token() {
    // No iniciar sesión aquí, ya se inició en index.php
    
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf'];
}

/**
 * Genera un campo hidden con token CSRF
 * 
 * Esta función genera un campo HTML hidden con el token CSRF
 * para ser incluido en formularios
 * 
 * @return string Campo hidden HTML
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verifica un token CSRF
 * 
 * Esta función verifica si el token CSRF proporcionado
 * coincide con el token almacenado en la sesión
 * 
 * @param string $token Token a verificar
 * @return bool True si el token es válido, false en caso contrario
 */
function verify_csrf($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/**
 * Redirige a una URL específica
 * 
 * Esta función realiza una redirección HTTP a la URL especificada
 * y termina la ejecución del script
 * 
 * @param string $url URL a la cual redirigir
 * @param int $statusCode Código de estado HTTP (por defecto 302)
 */
function redirect($url, $statusCode = 302) {
    // Si la URL ya es absoluta (http/https) o ya empieza con /, usarla tal cual
    if (strpos($url, 'http') === 0 || strpos($url, '/') === 0) {
        // URL ya está lista
    } else {
        // Si no, construir la URL completa
        $url = base_url() . ltrim($url, '/');
    }
    
    header("Location: " . $url, true, $statusCode);
    exit();
}

/**
 * Obtiene el valor de un parámetro de la URL
 * 
 * Esta función obtiene el valor de un parámetro específico
 * de la URL actual, con un valor por defecto si no existe
 * 
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto si no existe
 * @return mixed Valor del parámetro o valor por defecto
 */
function get_param($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Obtiene el valor de un parámetro POST
 * 
 * Esta función obtiene el valor de un parámetro específico
 * del método POST, con un valor por defecto si no existe
 * 
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto si no existe
 * @return mixed Valor del parámetro o valor por defecto
 */
function post_param($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Verifica si la petición es de tipo POST
 * 
 * @return bool True si es POST, false en caso contrario
 */
function is_post() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verifica si la petición es de tipo GET
 * 
 * @return bool True si es GET, false en caso contrario
 */
function is_get() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Formatea una fecha en formato legible
 * 
 * @param string $date Fecha a formatear
 * @param string $format Formato de salida
 * @return string Fecha formateada
 */
function format_date($date, $format = 'd/m/Y H:i') {
    return date($format, strtotime($date));
}

/**
 * Limpia y valida un string
 * 
 * @param string $string String a limpiar
 * @return string String limpio
 */
function clean_string($string) {
    return trim(strip_tags($string));
}

/**
 * Genera un slug a partir de un string
 * 
 * @param string $string String a convertir en slug
 * @return string Slug generado
 */
function generate_slug($string) {
    // Convertir a minúsculas
    $string = strtolower($string);
    
    // Reemplazar caracteres especiales
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    
    // Reemplazar espacios y guiones múltiples
    $string = preg_replace('/[\s-]+/', '-', $string);
    
    // Eliminar guiones al inicio y final
    return trim($string, '-');
}

/**
 * Establece un mensaje flash en la sesión
 * 
 * Los mensajes flash son mensajes temporales que se muestran una vez
 * y luego se eliminan automáticamente de la sesión
 * 
 * @param string $key Clave del mensaje
 * @param mixed $value Valor del mensaje
 * @return void
 */
function flash_set($key, $value) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    
    $_SESSION['flash'][$key] = $value;
}

/**
 * Obtiene un mensaje flash de la sesión
 * 
 * Esta función obtiene el mensaje flash y lo elimina de la sesión
 * para que solo se muestre una vez
 * 
 * @param string $key Clave del mensaje
 * @param mixed $default Valor por defecto si no existe
 * @return mixed Valor del mensaje o valor por defecto
 */
function flash_get($key, $default = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['flash'][$key])) {
        return $default;
    }
    
    $value = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    
    return $value;
}

/**
 * Verifica si existe un mensaje flash
 * 
 * @param string $key Clave del mensaje
 * @return bool True si existe, false en caso contrario
 */
function flash_has($key) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['flash'][$key]);
}

/**
 * Obtiene todos los mensajes flash
 * 
 * @return array Array de todos los mensajes flash
 */
function flash_all() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    
    return $messages;
}

/**
 * Establece un mensaje de éxito
 * 
 * @param string $message Mensaje de éxito
 * @return void
 */
function flash_success($message) {
    flash_set('success', $message);
}

/**
 * Establece un mensaje de error
 * 
 * @param string $message Mensaje de error
 * @return void
 */
function flash_error($message) {
    flash_set('error', $message);
}

/**
 * Establece un mensaje de información
 * 
 * @param string $message Mensaje de información
 * @return void
 */
function flash_info($message) {
    flash_set('info', $message);
}

/**
 * Establece un mensaje de advertencia
 * 
 * @param string $message Mensaje de advertencia
 * @return void
 */
function flash_warning($message) {
    flash_set('warning', $message);
}

/**
 * Verifica si un usuario está autenticado
 * 
 * @return bool True si está autenticado
 */
function is_user_authenticated() {
    return isset($_SESSION['auth']) && !empty($_SESSION['auth']);
}

/**
 * Obtiene el usuario actual autenticado
 * 
 * @return array|null Datos del usuario o null si no está autenticado
 */
function get_authenticated_user() {
    return $_SESSION['auth'] ?? null;
}
