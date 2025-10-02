<?php
/**
 * Repositorio de Usuarios en Memoria para la aplicación El Faro
 * 
 * Este repositorio maneja la persistencia de usuarios en memoria utilizando
 * la superglobal $_SESSION para almacenar los datos de usuarios.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

require_once __DIR__ . '/../Usuario.php';

class UsuarioRepoMem {
    
    /**
     * Clave para almacenar usuarios en la sesión
     * 
     * @var string
     */
    private const SESSION_KEY = 'usuarios';
    
    /**
     * Contador de IDs para usuarios
     * 
     * @var int
     */
    private static int $nextId = 1;
    
    /**
     * Constructor del repositorio
     * 
     * Inicializa la sesión si no está iniciada y prepara el almacenamiento
     * de usuarios en memoria.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Inicializar el array de usuarios si no existe
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = [];
        }
        
        // Inicializar el contador de IDs si no existe
        if (!isset($_SESSION['usuario_next_id'])) {
            $_SESSION['usuario_next_id'] = 1;
        }
        
        self::$nextId = $_SESSION['usuario_next_id'];
    }
    
    /**
     * Crea un nuevo usuario en el repositorio
     * 
     * @param Usuario $usuario Usuario a crear
     * @return Usuario Usuario creado con ID asignado
     * @throws Exception Si el email ya existe
     */
    public function create(Usuario $usuario): Usuario {
        // Verificar que el email sea único
        if ($this->findByEmail($usuario->email) !== null) {
            throw new Exception("El email {$usuario->email} ya está registrado");
        }
        
        // Asignar ID único
        $usuario->id = self::$nextId;
        
        // Validar el usuario antes de guardarlo
        $errores = $usuario->validar();
        if (!empty($errores)) {
            throw new Exception("Datos de usuario inválidos: " . implode(', ', $errores));
        }
        
        // Guardar en la sesión
        $_SESSION[self::SESSION_KEY][$usuario->id] = $usuario->toArray();
        
        // Incrementar contador de IDs
        self::$nextId++;
        $_SESSION['usuario_next_id'] = self::$nextId;
        
        return $usuario;
    }
    
    /**
     * Busca un usuario por su email
     * 
     * @param string $email Email del usuario a buscar
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public function findByEmail(string $email): ?Usuario {
        $usuarios = $_SESSION[self::SESSION_KEY] ?? [];
        
        foreach ($usuarios as $usuarioData) {
            if ($usuarioData['email'] === $email) {
                return Usuario::fromArray($usuarioData);
            }
        }
        
        return null;
    }
    
    /**
     * Busca un usuario por su ID
     * 
     * @param int $id ID del usuario a buscar
     * @return Usuario|null Usuario encontrado o null si no existe
     */
    public function findById(int $id): ?Usuario {
        $usuarios = $_SESSION[self::SESSION_KEY] ?? [];
        
        if (isset($usuarios[$id])) {
            return Usuario::fromArray($usuarios[$id]);
        }
        
        return null;
    }
    
    /**
     * Obtiene todos los usuarios del repositorio
     * 
     * @return array Array de objetos Usuario
     */
    public function all(): array {
        $usuarios = [];
        $usuariosData = $_SESSION[self::SESSION_KEY] ?? [];
        
        foreach ($usuariosData as $usuarioData) {
            $usuarios[] = Usuario::fromArray($usuarioData);
        }
        
        return $usuarios;
    }
    
    /**
     * Actualiza un usuario existente
     * 
     * @param Usuario $usuario Usuario a actualizar
     * @return bool True si se actualizó correctamente, false en caso contrario
     */
    public function update(Usuario $usuario): bool {
        $usuarios = $_SESSION[self::SESSION_KEY] ?? [];
        
        if (!isset($usuarios[$usuario->id])) {
            return false;
        }
        
        // Validar el usuario antes de actualizarlo
        $errores = $usuario->validar();
        if (!empty($errores)) {
            return false;
        }
        
        // Actualizar en la sesión
        $_SESSION[self::SESSION_KEY][$usuario->id] = $usuario->toArray();
        
        return true;
    }
    
    /**
     * Elimina un usuario del repositorio
     * 
     * @param int $id ID del usuario a eliminar
     * @return bool True si se eliminó correctamente, false en caso contrario
     */
    public function delete(int $id): bool {
        $usuarios = $_SESSION[self::SESSION_KEY] ?? [];
        
        if (!isset($usuarios[$id])) {
            return false;
        }
        
        unset($_SESSION[self::SESSION_KEY][$id]);
        return true;
    }
    
    /**
     * Obtiene el número total de usuarios
     * 
     * @return int Número total de usuarios
     */
    public function count(): int {
        return count($_SESSION[self::SESSION_KEY] ?? []);
    }
    
    /**
     * Busca usuarios por plan
     * 
     * @param string $plan Plan a buscar (free o premium)
     * @return array Array de usuarios con el plan especificado
     */
    public function findByPlan(string $plan): array {
        $usuarios = [];
        $usuariosData = $_SESSION[self::SESSION_KEY] ?? [];
        
        foreach ($usuariosData as $usuarioData) {
            if ($usuarioData['plan'] === $plan) {
                $usuarios[] = Usuario::fromArray($usuarioData);
            }
        }
        
        return $usuarios;
    }
    
    /**
     * Busca usuarios suscritos al newsletter
     * 
     * @return array Array de usuarios suscritos
     */
    public function findSuscritos(): array {
        $usuarios = [];
        $usuariosData = $_SESSION[self::SESSION_KEY] ?? [];
        
        foreach ($usuariosData as $usuarioData) {
            if ($usuarioData['suscrito']) {
                $usuarios[] = Usuario::fromArray($usuarioData);
            }
        }
        
        return $usuarios;
    }
    
    /**
     * Verifica si existe un usuario con el email especificado
     * 
     * @param string $email Email a verificar
     * @return bool True si existe, false en caso contrario
     */
    public function existsByEmail(string $email): bool {
        return $this->findByEmail($email) !== null;
    }
    
    /**
     * Obtiene estadísticas de usuarios
     * 
     * @return array Estadísticas de usuarios
     */
    public function getEstadisticas(): array {
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
    }
    
    /**
     * Limpia todos los usuarios del repositorio
     * 
     * @return void
     */
    public function clear(): void {
        $_SESSION[self::SESSION_KEY] = [];
        $_SESSION['usuario_next_id'] = 1;
        self::$nextId = 1;
    }
    
    /**
     * Obtiene usuarios ordenados por fecha de registro
     * 
     * @param string $orden Orden de clasificación (asc o desc)
     * @return array Array de usuarios ordenados
     */
    public function allOrderedByDate(string $orden = 'desc'): array {
        $usuarios = $this->all();
        
        usort($usuarios, function($a, $b) use ($orden) {
            $comparacion = strtotime($a->fechaRegistro) - strtotime($b->fechaRegistro);
            return $orden === 'desc' ? -$comparacion : $comparacion;
        });
        
        return $usuarios;
    }
    
    /**
     * Busca usuarios por nombre (búsqueda parcial)
     * 
     * @param string $nombre Nombre a buscar
     * @return array Array de usuarios que coinciden
     */
    public function searchByName(string $nombre): array {
        $usuarios = [];
        $usuariosData = $_SESSION[self::SESSION_KEY] ?? [];
        $nombreLower = strtolower($nombre);
        
        foreach ($usuariosData as $usuarioData) {
            if (strpos(strtolower($usuarioData['nombre']), $nombreLower) !== false) {
                $usuarios[] = Usuario::fromArray($usuarioData);
            }
        }
        
        return $usuarios;
    }
}
