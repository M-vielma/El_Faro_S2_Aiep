<?php
/**
 * Configuración de Supabase para El Faro
 * 
 * Este archivo contiene la configuración necesaria para conectarse a Supabase.
 * Las credenciales deben configurarse en variables de entorno.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

return [
    // URL del proyecto Supabase
    'url' => $_ENV['SUPABASE_URL'] ?? '',
    
    // API Key pública de Supabase
    'key' => $_ENV['SUPABASE_KEY'] ?? '',
    
    // API Key de servicio (para operaciones administrativas)
    'service_key' => $_ENV['SUPABASE_SERVICE_KEY'] ?? '',
    
    // Configuración de autenticación
    'auth' => [
        'scheme' => 'https',
        'auto_refresh_token' => true,
        'persist_session' => true,
        'detect_session_in_url' => true,
    ],
    
    // Configuración de base de datos
    'db' => [
        'schema' => 'public',
        'timeout' => 30,
    ],
    
    // Configuración de almacenamiento
    'storage' => [
        'bucket' => 'public',
    ],
    
    // Configuración de realtime
    'realtime' => [
        'enabled' => false,
    ],
];

