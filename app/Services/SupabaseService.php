<?php
/**
 * Servicio de Supabase para El Faro
 * 
 * Este servicio proporciona una interfaz centralizada para interactuar con Supabase,
 * incluyendo autenticación, base de datos y almacenamiento.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

namespace ElFaro\Services;

use Supabase\CreateClient;

class SupabaseService
{
    /**
     * Instancia única del cliente de Supabase
     * 
     * @var CreateClient|null
     */
    private static ?CreateClient $client = null;
    
    /**
     * Configuración de Supabase
     * 
     * @var array
     */
    private static array $config = [];
    
    /**
     * Obtiene la instancia del cliente de Supabase
     * 
     * @return CreateClient Cliente de Supabase
     * @throws \Exception Si la configuración no es válida
     */
    public static function getClient(): CreateClient
    {
        if (self::$client === null) {
            self::initialize();
        }
        
        return self::$client;
    }
    
    /**
     * Inicializa el cliente de Supabase
     * 
     * @return void
     * @throws \Exception Si la configuración no es válida
     */
    private static function initialize(): void
    {
        // Cargar configuración
        $configPath = __DIR__ . '/../../config/supabase.php';
        
        if (!file_exists($configPath)) {
            throw new \Exception('Archivo de configuración de Supabase no encontrado');
        }
        
        self::$config = require $configPath;
        
        // Validar configuración
        if (empty(self::$config['url']) || empty(self::$config['key'])) {
            throw new \Exception('Configuración de Supabase incompleta. Verifica SUPABASE_URL y SUPABASE_KEY en .env');
        }
        
        // Extraer reference_id de la URL
        // La URL es algo como: https://xxxxx.supabase.co
        $url = self::$config['url'];
        $url = rtrim($url, '/');
        $parts = explode('.', parse_url($url, PHP_URL_HOST));
        $reference_id = $parts[0] ?? '';
        
        if (empty($reference_id)) {
            throw new \Exception('No se pudo extraer el reference_id de la URL de Supabase');
        }
        
        // Crear cliente
        try {
            self::$client = new CreateClient(
                self::$config['key'],
                $reference_id,
                [],
                'supabase.co',
                'https'
            );
        } catch (\Exception $e) {
            throw new \Exception('Error al inicializar Supabase: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtiene el cliente de autenticación
     * 
     * @return \Supabase\GoTrue\GoTrueClient Cliente de autenticación
     */
    public static function auth()
    {
        return self::getClient()->auth;
    }
    
    /**
     * Obtiene el cliente de base de datos
     * 
     * @return \Supabase\Postgrest\PostgrestClient Cliente de base de datos
     */
    public static function db()
    {
        return self::getClient()->query;
    }
    
    /**
     * Obtiene el cliente de almacenamiento
     * 
     * @return \Supabase\Storage\StorageClient Cliente de almacenamiento
     */
    public static function storage()
    {
        return self::getClient()->storage;
    }
    
    /**
     * Verifica si Supabase está configurado correctamente
     * 
     * @return bool True si está configurado, false en caso contrario
     */
    public static function isConfigured(): bool
    {
        try {
            self::getClient();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Obtiene la configuración de Supabase
     * 
     * @return array Configuración
     */
    public static function getConfig(): array
    {
        return self::$config;
    }
}

