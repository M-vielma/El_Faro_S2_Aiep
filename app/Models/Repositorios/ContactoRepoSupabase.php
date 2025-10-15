<?php
/**
 * Repositorio de Mensajes de Contacto con Supabase para la aplicación El Faro
 * 
 * Este repositorio maneja la persistencia de mensajes de contacto utilizando Supabase
 * como backend de base de datos.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

require_once __DIR__ . '/../../Services/SupabaseService.php';

use ElFaro\Services\SupabaseService;

class ContactoRepoSupabase
{
    /**
     * Nombre de la tabla de mensajes de contacto en Supabase
     * 
     * @var string
     */
    private const TABLE_NAME = 'mensajes_contacto';
    
    /**
     * Constructor del repositorio
     * 
     * Inicializa la conexión con Supabase.
     */
    public function __construct()
    {
        // Verificar que Supabase esté configurado
        if (!SupabaseService::isConfigured()) {
            throw new \Exception('Supabase no está configurado correctamente');
        }
    }
    
    /**
     * Crea un nuevo mensaje de contacto
     * 
     * @param array $data Datos del mensaje
     * @return array Mensaje creado con ID asignado
     * @throws Exception Si hay un error
     */
    public function create(array $data): array
    {
        // Validar datos
        $errores = $this->validar($data);
        if (!empty($errores)) {
            throw new Exception("Datos de mensaje inválidos: " . implode(', ', $errores));
        }
        
        // Preparar datos para insertar
        $insertData = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'mensaje' => $data['mensaje'],
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Desconocida',
            'fecha' => date('Y-m-d H:i:s'),
            'leido' => false
        ];
        
        try {
            // Insertar en Supabase
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)->insert($insertData)->execute();
            
            // Verificar si la inserción fue exitosa
            if (isset($response->data) && is_array($response->data) && count($response->data) > 0) {
                return $response->data[0];
            }
            
            // Si no hay datos en la respuesta pero tampoco hay error, asumir éxito
            // y devolver los datos que intentamos insertar
            if (!isset($response->error)) {
                return $insertData;
            }
            
            throw new Exception('Error al crear el mensaje');
        } catch (\Exception $e) {
            // Log del error para debugging
            error_log('Error en ContactoRepoSupabase::create: ' . $e->getMessage());
            throw new Exception('Error al crear mensaje: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtiene todos los mensajes de contacto
     * 
     * @return array Array de mensajes
     */
    public function all(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->order('fecha', ['ascending' => false])
                ->execute();
            
            return $response->data ?? [];
        } catch (\Exception $e) {
            error_log('Error al obtener mensajes: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca un mensaje por su ID
     * 
     * @param int $id ID del mensaje a buscar
     * @return array|null Mensaje encontrado o null si no existe
     */
    public function findById(int $id): ?array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('id', $id)
                ->execute();
            
            return $response->data[0] ?? null;
        } catch (\Exception $e) {
            error_log('Error al buscar mensaje por ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene mensajes no leídos
     * 
     * @return array Array de mensajes no leídos
     */
    public function findNoLeidos(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('leido', false)
                ->order('fecha', ['ascending' => false])
                ->execute();
            
            return $response->data ?? [];
        } catch (\Exception $e) {
            error_log('Error al obtener mensajes no leídos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene mensajes leídos
     * 
     * @return array Array de mensajes leídos
     */
    public function findLeidos(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('leido', true)
                ->order('fecha', ['ascending' => false])
                ->execute();
            
            return $response->data ?? [];
        } catch (\Exception $e) {
            error_log('Error al obtener mensajes leídos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Marca un mensaje como leído
     * 
     * @param int $id ID del mensaje a marcar como leído
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function marcarComoLeido(int $id): bool
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->update(['leido' => true])
                ->eq('id', $id)
                ->execute();
            
            return isset($response->data[0]);
        } catch (\Exception $e) {
            error_log('Error al marcar mensaje como leído: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Elimina un mensaje
     * 
     * @param int $id ID del mensaje a eliminar
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete(int $id): bool
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->delete()
                ->eq('id', $id)
                ->execute();
            
            return true;
        } catch (\Exception $e) {
            error_log('Error al eliminar mensaje: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene el número total de mensajes
     * 
     * @return int Número total de mensajes
     */
    public function count(): int
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('id', ['count' => 'exact'])
                ->execute();
            
            return isset($response->count) ? (int)$response->count : 0;
        } catch (\Exception $e) {
            error_log('Error al contar mensajes: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtiene el número de mensajes no leídos
     * 
     * @return int Número de mensajes no leídos
     */
    public function countNoLeidos(): int
    {
        try {
            $db = SupabaseService::db();
            $response = $db->table(self::TABLE_NAME)
                ->select('id', ['count' => 'exact'])
                ->eq('leido', false)
                ->execute();
            
            return isset($response->count) ? (int)$response->count : 0;
        } catch (\Exception $e) {
            error_log('Error al contar mensajes no leídos: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtiene estadísticas de mensajes
     * 
     * @return array Estadísticas de mensajes
     */
    public function getEstadisticas(): array
    {
        try {
            $total = $this->count();
            $noLeidos = $this->countNoLeidos();
            $leidos = $total - $noLeidos;
            
            return [
                'total' => $total,
                'leidos' => $leidos,
                'no_leidos' => $noLeidos,
                'porcentaje_leidos' => $total > 0 ? round(($leidos / $total) * 100, 2) : 0
            ];
        } catch (\Exception $e) {
            error_log('Error al obtener estadísticas: ' . $e->getMessage());
            return [
                'total' => 0,
                'leidos' => 0,
                'no_leidos' => 0,
                'porcentaje_leidos' => 0
            ];
        }
    }
    
    /**
     * Valida los datos de un mensaje
     * 
     * @param array $data Datos del mensaje
     * @return array Array de errores de validación (vacío si no hay errores)
     */
    private function validar(array $data): array
    {
        $errores = [];
        
        // Validar nombre
        if (empty($data['nombre'])) {
            $errores[] = 'El nombre es requerido';
        } elseif (strlen($data['nombre']) < 2) {
            $errores[] = 'El nombre debe tener al menos 2 caracteres';
        } elseif (strlen($data['nombre']) > 100) {
            $errores[] = 'El nombre no puede tener más de 100 caracteres';
        }
        
        // Validar email
        if (empty($data['email'])) {
            $errores[] = 'El email es requerido';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es válido';
        }
        
        // Validar mensaje
        if (empty($data['mensaje'])) {
            $errores[] = 'El mensaje es requerido';
        } elseif (strlen($data['mensaje']) < 10) {
            $errores[] = 'El mensaje debe tener al menos 10 caracteres';
        } elseif (strlen($data['mensaje']) > 1000) {
            $errores[] = 'El mensaje no puede tener más de 1000 caracteres';
        }
        
        return $errores;
    }
}

