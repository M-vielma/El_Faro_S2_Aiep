<?php
/**
 * Router para la aplicación El Faro
 * 
 * Este router maneja el enrutamiento de la aplicación,
 * soportando métodos GET/POST y parámetros dinámicos.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class Router {
    
    /**
     * Array de rutas registradas
     * 
     * @var array
     */
    private $routes = [];
    
    /**
     * Array de rutas con parámetros dinámicos
     * 
     * @var array
     */
    private $paramRoutes = [];
    
    /**
     * Método HTTP actual
     * 
     * @var string
     */
    private $method;
    
    /**
     * URI actual
     * 
     * @var string
     */
    private $uri;
    
    /**
     * Constructor del Router
     * 
     * Inicializa el router y obtiene el método HTTP y URI actuales
     */
    public function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->getCurrentUri();
    }
    
    /**
     * Registra una ruta GET
     * 
     * @param string $route Ruta a registrar
     * @param string $handler Controlador@método
     */
    public function get($route, $handler) {
        $this->addRoute('GET', $route, $handler);
    }
    
    /**
     * Registra una ruta POST
     * 
     * @param string $route Ruta a registrar
     * @param string $handler Controlador@método
     */
    public function post($route, $handler) {
        $this->addRoute('POST', $route, $handler);
    }
    
    /**
     * Agrega una ruta al router
     * 
     * @param string $method Método HTTP
     * @param string $route Ruta
     * @param string $handler Controlador@método
     */
    private function addRoute($method, $route, $handler) {
        // Verificar si la ruta tiene parámetros dinámicos
        if (strpos($route, '{') !== false) {
            $this->paramRoutes[$method][$route] = $handler;
        } else {
            $this->routes[$method][$route] = $handler;
        }
    }
    
    /**
     * Obtiene la URI actual
     * 
     * @return string URI actual
     */
    private function getCurrentUri() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remover query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remover la base URL si existe
        $baseUrl = '/Elfaro_taller_s2/public';
        if (strpos($uri, $baseUrl) === 0) {
            $uri = substr($uri, strlen($baseUrl));
        }
        
        return $uri ?: '/';
    }
    
    /**
     * Despacha la ruta actual
     * 
     * Busca la ruta coincidente y ejecuta el controlador correspondiente
     */
    public function dispatch() {
        // Buscar ruta exacta primero
        if (isset($this->routes[$this->method][$this->uri])) {
            $handler = $this->routes[$this->method][$this->uri];
            $this->executeHandler($handler);
            return;
        }
        
        // Buscar rutas con parámetros
        if (isset($this->paramRoutes[$this->method])) {
            foreach ($this->paramRoutes[$this->method] as $route => $handler) {
                if ($this->matchRoute($route, $this->uri)) {
                    $this->executeHandler($handler);
                    return;
                }
            }
        }
        
        // Si no se encuentra ninguna ruta, mostrar 404
        $this->show404();
    }
    
    /**
     * Verifica si una ruta con parámetros coincide con la URI actual
     * 
     * @param string $route Ruta con parámetros
     * @param string $uri URI actual
     * @return bool True si coincide, false en caso contrario
     */
    private function matchRoute($route, $uri) {
        // Convertir parámetros {id} a expresiones regulares
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';
        
        if (preg_match($pattern, $uri, $matches)) {
            // Extraer nombres de parámetros
            preg_match_all('/\{([^}]+)\}/', $route, $paramNames);
            
            // Asignar valores a parámetros
            for ($i = 1; $i < count($matches); $i++) {
                $paramName = $paramNames[1][$i - 1];
                $_GET[$paramName] = $matches[$i];
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Ejecuta el handler (controlador@método)
     * 
     * @param string $handler Controlador@método
     */
    private function executeHandler($handler) {
        // Verificar formato del handler
        if (strpos($handler, '@') === false) {
            throw new Exception('Formato de handler inválido. Debe ser Controlador@método');
        }
        
        list($controller, $method) = explode('@', $handler);
        
        // Verificar si el controlador existe
        $controllerFile = __DIR__ . '/../Controllers/' . $controller . '.php';
        if (!file_exists($controllerFile)) {
            throw new Exception("Controlador {$controller} no encontrado");
        }
        
        // Incluir el controlador
        require_once $controllerFile;
        
        // Verificar si la clase existe
        if (!class_exists($controller)) {
            throw new Exception("Clase {$controller} no encontrada");
        }
        
        // Crear instancia del controlador
        $controllerInstance = new $controller();
        
        // Verificar si el método existe
        if (!method_exists($controllerInstance, $method)) {
            throw new Exception("Método {$method} no encontrado en {$controller}");
        }
        
        // Ejecutar el método con parámetros si los hay
        $reflection = new ReflectionMethod($controllerInstance, $method);
        $params = $reflection->getParameters();
        
        if (count($params) > 0) {
            // Pasar parámetros extraídos de la URL
            $args = [];
            foreach ($params as $param) {
                $paramName = $param->getName();
                if (isset($_GET[$paramName])) {
                    $args[] = $_GET[$paramName];
                } else {
                    $args[] = null;
                }
            }
            $controllerInstance->$method(...$args);
        } else {
            $controllerInstance->$method();
        }
    }
    
    /**
     * Muestra página 404
     */
    private function show404() {
        http_response_code(404);
        
        // Incluir el controlador de errores
        $errorController = __DIR__ . '/../Controllers/ErrorController.php';
        if (file_exists($errorController)) {
            require_once $errorController;
            $controller = new ErrorController();
            $controller->notFound();
        } else {
            // Fallback si no existe el controlador de errores
            echo '<h1>404 - Página no encontrada</h1>';
            echo '<p>La página que buscas no existe.</p>';
        }
    }
    
    /**
     * Obtiene todas las rutas registradas
     * 
     * @return array Rutas registradas
     */
    public function getRoutes() {
        return [
            'exact' => $this->routes,
            'param' => $this->paramRoutes
        ];
    }
    
    /**
     * Obtiene información de debug del router
     * 
     * @return array Información de debug
     */
    public function getDebugInfo() {
        return [
            'method' => $this->method,
            'uri' => $this->uri,
            'routes_count' => count($this->routes[$this->method] ?? []) + count($this->paramRoutes[$this->method] ?? []),
            'registered_routes' => $this->getRoutes()
        ];
    }
}
