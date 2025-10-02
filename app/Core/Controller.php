<?php
/**
 * Clase base Controller para la aplicación El Faro
 * 
 * Esta clase proporciona funcionalidad base para todos los controladores
 * de la aplicación, incluyendo métodos para renderizar vistas y manejar datos.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class Controller {
    
    /**
     * Instancia del View para renderizado
     * 
     * @var View
     */
    protected $view;
    
    /**
     * Datos a pasar a las vistas
     * 
     * @var array
     */
    protected $data = [];
    
    /**
     * Constructor del Controller
     * 
     * Inicializa la instancia de View y carga los helpers
     */
    public function __construct() {
        // Cargar helpers
        require_once __DIR__ . '/../Helpers/helpers.php';
        
        // Inicializar View
        $this->view = new View();
        
        // Inicializar datos comunes
        $this->initializeCommonData();
    }
    
    /**
     * Renderiza una vista con datos
     * 
     * @param string $template Nombre del template (sin extensión)
     * @param array $data Datos adicionales a pasar a la vista
     */
    protected function view($template, $data = []) {
        // Combinar datos del controlador con datos adicionales
        $viewData = array_merge($this->data, $data);
        
        // Renderizar la vista
        $this->view->render($template, $viewData);
    }
    
    /**
     * Renderiza una vista sin layout
     * 
     * @param string $template Nombre del template
     * @param array $data Datos a pasar a la vista
     */
    protected function renderPartial($template, $data = []) {
        $viewData = array_merge($this->data, $data);
        $this->view->renderPartial($template, $viewData);
    }
    
    /**
     * Establece datos para las vistas
     * 
     * @param string $key Clave del dato
     * @param mixed $value Valor del dato
     */
    protected function setData($key, $value) {
        $this->data[$key] = $value;
    }
    
    /**
     * Obtiene un dato de la vista
     * 
     * @param string $key Clave del dato
     * @param mixed $default Valor por defecto
     * @return mixed Valor del dato
     */
    protected function getData($key, $default = null) {
        return $this->data[$key] ?? $default;
    }
    
    /**
     * Inicializa datos comunes para todas las vistas
     */
    private function initializeCommonData() {
        // Cargar configuración con fallback seguro
        $configPath = __DIR__ . '/../../config/app.php';
        $config = is_file($configPath) ? include $configPath : null;
        
        if (!is_array($config)) {
            // Fallback para no romper la app si falla la carga
            $config = [
                'app_name' => 'El Faro',
                'base_url' => 'http://localhost/Elfaro_taller_s2/public',
                'ui' => 'bootstrap',
            ];
        }
        
        // Datos comunes (usando las CLAVES del config, no valores literales)
        $this->data = [
            'app_name' => $config['app_name'] ?? 'El Faro',
            'base_url' => $config['base_url'] ?? '/',
            'ui' => $config['ui'] ?? 'bootstrap',
            'current_url' => $_SERVER['REQUEST_URI'] ?? '/',
            'csrf_token' => function_exists('csrf_token') ? csrf_token() : '',
            'page_title' => $config['app_name'] ?? 'El Faro',
            'meta_description' => 'Periódico digital El Faro - Noticias actuales y confiables',
            'meta_keywords' => 'noticias, periódico, El Faro, actualidad, información'
        ];
    }
    
    /**
     * Redirige a una URL específica
     * 
     * @param string $url URL a la cual redirigir
     * @param int $statusCode Código de estado HTTP
     */
    protected function redirect($url, $statusCode = 302) {
        redirect($url, $statusCode);
    }
    
    /**
     * Redirige a la página anterior
     */
    protected function redirectBack() {
        $referer = $_SERVER['HTTP_REFERER'] ?? base_url();
        $this->redirect($referer);
    }
    
    /**
     * Establece el título de la página
     * 
     * @param string $title Título de la página
     */
    protected function setTitle($title) {
        $this->data['page_title'] = $title . ' - ' . $this->data['app_name'];
    }
    
    /**
     * Establece la descripción meta de la página
     * 
     * @param string $description Descripción meta
     */
    protected function setMetaDescription($description) {
        $this->data['meta_description'] = $description;
    }
    
    /**
     * Establece las palabras clave meta de la página
     * 
     * @param string $keywords Palabras clave meta
     */
    protected function setMetaKeywords($keywords) {
        $this->data['meta_keywords'] = $keywords;
    }
    
    /**
     * Verifica si la petición es AJAX
     * 
     * @return bool True si es AJAX, false en caso contrario
     */
    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Responde con JSON
     * 
     * @param mixed $data Datos a enviar
     * @param int $statusCode Código de estado HTTP
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Responde con texto plano
     * 
     * @param string $text Texto a enviar
     * @param int $statusCode Código de estado HTTP
     */
    protected function text($text, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: text/plain');
        echo $text;
        exit();
    }
    
    /**
     * Valida datos de entrada
     * 
     * @param array $data Datos a validar
     * @param array $rules Reglas de validación
     * @return array Errores de validación
     */
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            // Verificar si es requerido
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "El campo {$field} es requerido";
                continue;
            }
            
            // Verificar longitud mínima
            if (preg_match('/min:(\d+)/', $rule, $matches)) {
                $minLength = (int)$matches[1];
                if (strlen($value) < $minLength) {
                    $errors[$field] = "El campo {$field} debe tener al menos {$minLength} caracteres";
                }
            }
            
            // Verificar longitud máxima
            if (preg_match('/max:(\d+)/', $rule, $matches)) {
                $maxLength = (int)$matches[1];
                if (strlen($value) > $maxLength) {
                    $errors[$field] = "El campo {$field} no puede tener más de {$maxLength} caracteres";
                }
            }
            
            // Verificar email
            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "El campo {$field} debe ser un email válido";
            }
        }
        
        return $errors;
    }
    
    /**
     * Obtiene datos de la sesión
     * 
     * @param string $key Clave del dato
     * @param mixed $default Valor por defecto
     * @return mixed Valor del dato
     */
    protected function session($key, $default = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Establece datos en la sesión
     * 
     * @param string $key Clave del dato
     * @param mixed $value Valor del dato
     */
    protected function setSession($key, $value) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION[$key] = $value;
    }
    
    /**
     * Elimina un dato de la sesión
     * 
     * @param string $key Clave del dato
     */
    protected function unsetSession($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION[$key]);
    }
    
    /**
     * Genera un enlace activo con icono
     * 
     * @param string $url URL del enlace
     * @param string $text Texto del enlace con icono
     * @param string $activeClass Clase para enlace activo
     * @return string HTML del enlace
     */
    protected function activeLink(string $url, string $text, string $activeClass = 'active'): string {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $isActive = ($currentPath === $url || ($url !== '/' && strpos($currentPath, $url) === 0));
        
        $class = $isActive ? "nav-link $activeClass" : 'nav-link';
        
        return "<a href=\"{$this->url($url)}\" class=\"$class\">$text</a>";
    }
    
    /**
     * Genera un enlace simple sin iconos (para casos especiales)
     * 
     * @param string $url URL del enlace
     * @param string $text Texto del enlace
     * @param string $activeClass Clase para enlace activo
     * @return string HTML del enlace
     */
    protected function simpleLink(string $url, string $text, string $activeClass = 'active'): string {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $isActive = ($currentPath === $url || ($url !== '/' && strpos($currentPath, $url) === 0));
        
        $class = $isActive ? "nav-link $activeClass" : 'nav-link';
        
        return "<a href=\"{$this->url($url)}\" class=\"$class\">$text</a>";
    }
    
    /**
     * Verifica si una ruta está activa
     * 
     * @param string $url URL a verificar
     * @return bool True si la ruta está activa
     */
    protected function isActive(string $url): bool {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return ($currentPath === $url || ($url !== '/' && strpos($currentPath, $url) === 0));
    }
}
