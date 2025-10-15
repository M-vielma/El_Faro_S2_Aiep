<?php
/**
 * Repositorio de Usuarios con Supabase para la aplicación El Faro
 * 
 * Este repositorio maneja la persistencia de usuarios utilizando Supabase
 * como backend de base de datos.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

require_once __DIR__ . '/../Usuario.php';
require_once __DIR__ . '/../../Services/SupabaseService.php';

use ElFaro\Services\SupabaseService;

class UsuarioRepoSupabase
{
    /**
     * Nombre de la tabla de usuarios en Supabase
     * 
     * @var string
     */
    private const TABLE_NAME = 'usuarios';
    
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
     * Crea un nuevo usuario en el repositorio
     * 
     * @param Usuario $usuario Usuario a crear
     * @return Usuario Usuario creado con ID asignado
     * @throws Exception Si el email ya existe o hay un error
     */
    public function create(Usuario $usuario): Usuario
    {
        // Verificar que el email sea único
        if ($this->findByEmail($usuario->email) !== null) {
            throw new Exception("El email {$usuario->email} ya está registrado");
        }
        
        // Validar el usuario antes de guardarlo
        $errores = $usuario->validar();
        if (!empty($errores)) {
            throw new Exception("Datos de usuario inválidos: " . implode(', ', $errores));
        }
        
        // Preparar datos para insertar
        $data = [
            'id' => $usuario->id, // Incluir el ID del usuario de Auth
            'nombre' => $usuario->nombre,
            'email' => $usuario->email,
            'password_hash' => $usuario->password_hash,
            'suscrito' => $usuario->suscrito,
            'plan' => $usuario->plan,
            'fecha_registro' => $usuario->fechaRegistro
        ];
        
        try {
            // Insertar en Supabase
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)->insert($data)->execute();
            
            // Obtener el usuario creado
            if (isset($response->data[0])) {
                $usuarioData = $response->data[0];
                return Usuario::fromArray([
                    'id' => $usuarioData['id'],
                    'nombre' => $usuarioData['nombre'],
                    'email' => $usuarioData['email'],
                    'password_hash' => $usuarioData['password_hash'],
                    'suscrito' => $usuarioData['suscrito'],
                    'plan' => $usuarioData['plan'],
                    'fechaRegistro' => $usuarioData['fecha_registro']
                ]);
            }
            
            throw new Exception('Error al crear el usuario');
        } catch (\Exception $e) {
            throw new Exception('Error al crear usuario: ' . $e->getMessage());
        }
    }
    
    /**
     * Busca un usuario por su email
     * 
     * @param string $email Email del usuario a buscar
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public function findByEmail(string $email): ?Usuario
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('email', $email)
                ->execute();
            
            if (isset($response->data[0])) {
                $usuarioData = $response->data[0];
                return Usuario::fromArray([
                    'id' => $usuarioData['id'],
                    'nombre' => $usuarioData['nombre'],
                    'email' => $usuarioData['email'],
                    'password_hash' => $usuarioData['password_hash'],
                    'suscrito' => $usuarioData['suscrito'],
                    'plan' => $usuarioData['plan'],
                    'fechaRegistro' => $usuarioData['fecha_registro']
                ]);
            }
            
            return null;
        } catch (\Exception $e) {
            error_log('Error al buscar usuario por email: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Busca un usuario por su ID
     * 
     * @param string $id ID del usuario a buscar (UUID)
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public function findById(string $id): ?Usuario
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('id', $id)
                ->execute();
            
            if (isset($response->data[0])) {
                $usuarioData = $response->data[0];
                return Usuario::fromArray([
                    'id' => $usuarioData['id'],
                    'nombre' => $usuarioData['nombre'],
                    'email' => $usuarioData['email'],
                    'password_hash' => $usuarioData['password_hash'],
                    'suscrito' => $usuarioData['suscrito'],
                    'plan' => $usuarioData['plan'],
                    'fechaRegistro' => $usuarioData['fecha_registro']
                ]);
            }
            
            return null;
        } catch (\Exception $e) {
            error_log('Error al buscar usuario por ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtiene todos los usuarios del repositorio
     * 
     * @return array Array de objetos Usuario
     */
    public function all(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)->select('*')->execute();
            
            $usuarios = [];
            if (isset($response->data)) {
                foreach ($response->data as $usuarioData) {
                    $usuarios[] = Usuario::fromArray([
                        'id' => $usuarioData['id'],
                        'nombre' => $usuarioData['nombre'],
                        'email' => $usuarioData['email'],
                        'password_hash' => $usuarioData['password_hash'],
                        'suscrito' => $usuarioData['suscrito'],
                        'plan' => $usuarioData['plan'],
                        'fechaRegistro' => $usuarioData['fecha_registro']
                    ]);
                }
            }
            
            return $usuarios;
        } catch (\Exception $e) {
            error_log('Error al obtener usuarios: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Actualiza un usuario existente
     * 
     * @param Usuario $usuario Usuario a actualizar
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function update(Usuario $usuario): bool
    {
        try {
            // Validar el usuario antes de actualizarlo
            $errores = $usuario->validar();
            if (!empty($errores)) {
                return false;
            }
            
            // Preparar datos para actualizar
            $data = [
                'nombre' => $usuario->nombre,
                'email' => $usuario->email,
                'password_hash' => $usuario->password_hash,
                'suscrito' => $usuario->suscrito,
                'plan' => $usuario->plan
            ];
            
            // Actualizar en Supabase
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->update($data)
                ->eq('id', $usuario->id)
                ->execute();
            
            return isset($response->data[0]);
        } catch (\Exception $e) {
            error_log('Error al actualizar usuario: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Elimina un usuario del repositorio
     * 
     * @param int $id ID del usuario a eliminar
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
            error_log('Error al eliminar usuario: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene el número total de usuarios
     * 
     * @return int Número total de usuarios
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
            error_log('Error al contar usuarios: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Busca usuarios por plan
     * 
     * @param string $plan Plan a buscar (free, basic, premium, vip)
     * @return array Array de usuarios con el plan especificado
     */
    public function findByPlan(string $plan): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('plan', $plan)
                ->execute();
            
            $usuarios = [];
            if (isset($response->data)) {
                foreach ($response->data as $usuarioData) {
                    $usuarios[] = Usuario::fromArray([
                        'id' => $usuarioData['id'],
                        'nombre' => $usuarioData['nombre'],
                        'email' => $usuarioData['email'],
                        'password_hash' => $usuarioData['password_hash'],
                        'suscrito' => $usuarioData['suscrito'],
                        'plan' => $usuarioData['plan'],
                        'fechaRegistro' => $usuarioData['fecha_registro']
                    ]);
                }
            }
            
            return $usuarios;
        } catch (\Exception $e) {
            error_log('Error al buscar usuarios por plan: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca usuarios suscritos al newsletter
     * 
     * @return array Array de usuarios suscritos
     */
    public function findSuscritos(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('suscrito', true)
                ->execute();
            
            $usuarios = [];
            if (isset($response->data)) {
                foreach ($response->data as $usuarioData) {
                    $usuarios[] = Usuario::fromArray([
                        'id' => $usuarioData['id'],
                        'nombre' => $usuarioData['nombre'],
                        'email' => $usuarioData['email'],
                        'password_hash' => $usuarioData['password_hash'],
                        'suscrito' => $usuarioData['suscrito'],
                        'plan' => $usuarioData['plan'],
                        'fechaRegistro' => $usuarioData['fecha_registro']
                    ]);
                }
            }
            
            return $usuarios;
        } catch (\Exception $e) {
            error_log('Error al buscar usuarios suscritos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Verifica si existe un usuario con el email especificado
     * 
     * @param string $email Email a verificar
     * @return bool True si existe, false en caso contrario
     */
    public function existsByEmail(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }
    
    /**
     * Obtiene estadísticas de usuarios
     * 
     * @return array Estadísticas de usuarios
     */
    public function getEstadisticas(): array
    {
        try {
            $usuarios = $this->all();
            $total = count($usuarios);
            $premium = 0;
            $suscritos = 0;
            
            foreach ($usuarios as $usuario) {
                if ($usuario->esPremium()) {
                    $premium++;
                }
                if ($usuario->estaSuscrito()) {
                    $suscritos++;
                }
            }
            
            return [
                'total' => $total,
                'premium' => $premium,
                'free' => $total - $premium,
                'suscritos' => $suscritos,
                'no_suscritos' => $total - $suscritos,
                'porcentaje_premium' => $total > 0 ? round(($premium / $total) * 100, 2) : 0,
                'porcentaje_suscritos' => $total > 0 ? round(($suscritos / $total) * 100, 2) : 0
            ];
        } catch (\Exception $e) {
            error_log('Error al obtener estadísticas: ' . $e->getMessage());
            return [
                'total' => 0,
                'premium' => 0,
                'free' => 0,
                'suscritos' => 0,
                'no_suscritos' => 0,
                'porcentaje_premium' => 0,
                'porcentaje_suscritos' => 0
            ];
        }
    }
    
    /**
     * Obtiene usuarios ordenados por fecha de registro
     * 
     * @param string $orden Orden de clasificación (asc o desc)
     * @return array Array de usuarios ordenados
     */
    public function allOrderedByDate(string $orden = 'desc'): array
    {
        try {
            $db = SupabaseService::db();
            $query = $db->from(self::TABLE_NAME)->select('*');
            
            if ($orden === 'desc') {
                $query = $query->order('fecha_registro', ['ascending' => false]);
            } else {
                $query = $query->order('fecha_registro', ['ascending' => true]);
            }
            
            $response = $query->execute();
            
            $usuarios = [];
            if (isset($response->data)) {
                foreach ($response->data as $usuarioData) {
                    $usuarios[] = Usuario::fromArray([
                        'id' => $usuarioData['id'],
                        'nombre' => $usuarioData['nombre'],
                        'email' => $usuarioData['email'],
                        'password_hash' => $usuarioData['password_hash'],
                        'suscrito' => $usuarioData['suscrito'],
                        'plan' => $usuarioData['plan'],
                        'fechaRegistro' => $usuarioData['fecha_registro']
                    ]);
                }
            }
            
            return $usuarios;
        } catch (\Exception $e) {
            error_log('Error al obtener usuarios ordenados: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca usuarios por nombre (búsqueda parcial)
     * 
     * @param string $nombre Nombre a buscar
     * @return array Array de usuarios que coinciden
     */
    public function searchByName(string $nombre): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->ilike('nombre', '%' . $nombre . '%')
                ->execute();
            
            $usuarios = [];
            if (isset($response->data)) {
                foreach ($response->data as $usuarioData) {
                    $usuarios[] = Usuario::fromArray([
                        'id' => $usuarioData['id'],
                        'nombre' => $usuarioData['nombre'],
                        'email' => $usuarioData['email'],
                        'password_hash' => $usuarioData['password_hash'],
                        'suscrito' => $usuarioData['suscrito'],
                        'plan' => $usuarioData['plan'],
                        'fechaRegistro' => $usuarioData['fecha_registro']
                    ]);
                }
            }
            
            return $usuarios;
        } catch (\Exception $e) {
            error_log('Error al buscar usuarios por nombre: ' . $e->getMessage());
            return [];
        }
    }
}

