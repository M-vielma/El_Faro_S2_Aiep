<?php
/**
 * Clase View para el renderizado de vistas en la aplicación El Faro
 * 
 * Esta clase maneja el renderizado de vistas, incluyendo el layout principal
 * y la inclusión de partials como navbar y footer.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class View {
    
    /**
     * Ruta base de las vistas
     * 
     * @var string
     */
    private $viewsPath;
    
    /**
     * Ruta del layout principal
     * 
     * @var string
     */
    private $layoutPath;
    
    /**
     * Constructor de la clase View
     * 
     * Inicializa las rutas de las vistas y el layout
     */
    public function __construct() {
        $this->viewsPath = __DIR__ . '/../../views/';
        $this->layoutPath = $this->viewsPath . 'layout/main.php';
    }
    
    /**
     * Renderiza una vista con el layout principal
     * 
     * @param string $template Nombre del template (sin extensión)
     * @param array $data Datos a pasar a la vista
     */
    public function render($template, $data = []) {
        // Extraer datos para que estén disponibles en la vista
        extract($data);
        
        // Verificar si el layout existe
        if (!file_exists($this->layoutPath)) {
            throw new Exception("Layout principal no encontrado: {$this->layoutPath}");
        }
        
        // Capturar el contenido de la vista
        $content = $this->renderTemplate($template, $data);
        
        // Incluir el layout principal con el contenido
        include $this->layoutPath;
    }
    
    /**
     * Renderiza una vista sin layout (partial)
     * 
     * @param string $template Nombre del template
     * @param array $data Datos a pasar a la vista
     * @return string Contenido renderizado
     */
    public function renderPartial($template, $data = []) {
        return $this->renderTemplate($template, $data);
    }
    
    /**
     * Renderiza un template específico
     * 
     * @param string $template Nombre del template
     * @param array $data Datos a pasar a la vista
     * @return string Contenido renderizado
     */
    private function renderTemplate($template, $data = []) {
        // Construir la ruta del template
        $templatePath = $this->viewsPath . $template . '.php';
        
        // Verificar si el template existe
        if (!file_exists($templatePath)) {
            throw new Exception("Template no encontrado: {$templatePath}");
        }
        
        // Extraer datos para que estén disponibles en la vista
        extract($data);
        
        // Iniciar captura de salida
        ob_start();
        
        // Incluir el template
        include $templatePath;
        
        // Obtener el contenido capturado
        $content = ob_get_clean();
        
        return $content;
    }
    
    /**
     * Incluye un partial (navbar, footer, etc.)
     * 
     * @param string $partial Nombre del partial
     * @param array $data Datos a pasar al partial
     */
    public function includePartial($partial, $data = []) {
        $partialPath = $this->viewsPath . 'layout/partials/' . $partial . '.php';
        
        if (!file_exists($partialPath)) {
            throw new Exception("Partial no encontrado: {$partialPath}");
        }
        
        // Extraer datos
        extract($data);
        
        // Incluir el partial
        include $partialPath;
    }
    
    /**
     * Incluye el navbar
     * 
     * @param array $data Datos adicionales para el navbar
     */
    public function includeNavbar($data = []) {
        $this->includePartial('navbar', $data);
    }
    
    /**
     * Incluye el footer
     * 
     * @param array $data Datos adicionales para el footer
     */
    public function includeFooter($data = []) {
        $this->includePartial('footer', $data);
    }
    
    /**
     * Escapa un string para prevenir XSS
     * 
     * @param string $string String a escapar
     * @return string String escapado
     */
    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Genera una URL completa (para enlaces internos de la app)
     * 
     * @param string $path Ruta adicional
     * @return string URL completa
     */
    public function url($path = '') {
        return app_url($path);
    }
    
    /**
     * Genera un enlace HTML
     * 
     * @param string $url URL del enlace
     * @param string $text Texto del enlace
     * @param array $attributes Atributos adicionales
     * @return string Enlace HTML
     */
    public function link($url, $text, $attributes = []) {
        $attrString = '';
        foreach ($attributes as $key => $value) {
            $attrString .= " {$key}=\"{$this->escape($value)}\"";
        }
        
        return "<a href=\"{$this->escape($url)}\"{$attrString}>{$this->escape($text)}</a>";
    }
    
    /**
     * Genera un enlace con clases CSS
     * 
     * @param string $url URL del enlace
     * @param string $text Texto del enlace
     * @param string $class Clase CSS
     * @param array $attributes Atributos adicionales
     * @return string Enlace HTML
     */
    public function linkWithClass($url, $text, $class = '', $attributes = []) {
        if (!empty($class)) {
            $attributes['class'] = $class;
        }
        
        return $this->link($url, $text, $attributes);
    }
    
    /**
     * Genera un enlace activo si la URL coincide
     * 
     * @param string $url URL del enlace
     * @param string $text Texto del enlace
     * @param string $activeClass Clase para enlace activo
     * @param array $attributes Atributos adicionales
     * @return string Enlace HTML
     */
    public function activeLink($url, $text, $activeClass = 'active', $attributes = []) {
        $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
        $baseUrl = '/Elfaro_taller_s2/public';
        
        // Remover la base URL si existe
        if (strpos($currentUrl, $baseUrl) === 0) {
            $currentUrl = substr($currentUrl, strlen($baseUrl));
        }
        
        $currentUrl = $currentUrl ?: '/';
        
        // Verificar si es la URL activa
        if ($currentUrl === $url) {
            if (isset($attributes['class'])) {
                $attributes['class'] .= ' ' . $activeClass;
            } else {
                $attributes['class'] = $activeClass;
            }
        }
        
        return $this->link($url, $text, $attributes);
    }
    
    /**
     * Genera un token CSRF para formularios
     * 
     * @return string Token CSRF
     */
    public function csrfToken() {
        return csrf_token();
    }
    
    /**
     * Genera un campo hidden con token CSRF
     * 
     * @return string Campo hidden HTML
     */
    public function csrfField() {
        return '<input type="hidden" name="csrf_token" value="' . $this->csrfToken() . '">';
    }
    
    /**
     * Formatea una fecha
     * 
     * @param string $date Fecha a formatear
     * @param string $format Formato de salida
     * @return string Fecha formateada
     */
    public function formatDate($date, $format = 'd/m/Y H:i') {
        return format_date($date, $format);
    }
    
    /**
     * Trunca un texto a una longitud específica
     * 
     * @param string $text Texto a truncar
     * @param int $length Longitud máxima
     * @param string $suffix Sufijo para texto truncado
     * @return string Texto truncado
     */
    public function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
    
    /**
     * Genera un slug a partir de un texto
     * 
     * @param string $text Texto a convertir
     * @return string Slug generado
     */
    public function slug($text) {
        return generate_slug($text);
    }
    
    /**
     * Verifica si una variable existe y no está vacía
     * 
     * @param mixed $var Variable a verificar
     * @return bool True si existe y no está vacía
     */
    public function has($var) {
        return isset($var) && !empty($var);
    }
    
    /**
     * Obtiene el valor de una variable o un valor por defecto
     * 
     * @param mixed $var Variable a obtener
     * @param mixed $default Valor por defecto
     * @return mixed Valor de la variable o valor por defecto
     */
    public function get($var, $default = '') {
        return $var ?? $default;
    }
    
    /**
     * Verifica si una URL está activa
     * 
     * @param string $url URL a verificar
     * @return bool True si está activa
     */
    public function isActive($url) {
        $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        return ($currentPath === $url || ($url !== '/' && strpos($currentPath, $url) === 0));
    }
}
