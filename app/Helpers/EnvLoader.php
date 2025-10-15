<?php
/**
 * Cargador de Variables de Entorno para El Faro
 * 
 * Este archivo carga las variables de entorno desde el archivo .env
 * y las hace disponibles para la aplicación.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

class EnvLoader
{
    /**
     * Carga las variables de entorno desde el archivo .env
     * 
     * @param string $path Ruta al archivo .env
     * @return void
     */
    public static function load($path = null)
    {
        // Si no se especifica una ruta, usar la raíz del proyecto
        if ($path === null) {
            $path = __DIR__ . '/../../.env';
        }
        
        // Verificar si el archivo existe
        if (!file_exists($path)) {
            throw new Exception("Archivo .env no encontrado en: {$path}");
        }
        
        // Leer el archivo línea por línea
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignorar comentarios
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Separar clave y valor
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                
                // Limpiar espacios
                $key = trim($key);
                $value = trim($value);
                
                // Remover comillas si existen
                $value = trim($value, '"\'');
                
                // Establecer la variable de entorno
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }
    
    /**
     * Obtiene una variable de entorno
     * 
     * @param string $key Clave de la variable
     * @param mixed $default Valor por defecto
     * @return mixed Valor de la variable
     */
    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
    
    /**
     * Verifica si una variable de entorno existe
     * 
     * @param string $key Clave de la variable
     * @return bool True si existe, false en caso contrario
     */
    public static function has($key)
    {
        return isset($_ENV[$key]) || getenv($key) !== false;
    }
}

