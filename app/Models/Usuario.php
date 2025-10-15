<?php
/**
 * Modelo Usuario para la aplicación El Faro
 * 
 * Este modelo representa a un usuario del sistema con todas sus propiedades
 * y métodos necesarios para el manejo de datos de usuarios.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class Usuario {
    
    /**
     * ID único del usuario (UUID de Supabase)
     * 
     * @var string
     */
    public string $id;
    
    /**
     * Nombre completo del usuario
     * 
     * @var string
     */
    public string $nombre;
    
    /**
     * Email del usuario (único en el sistema)
     * 
     * @var string
     */
    public string $email;
    
    /**
     * Hash de la contraseña del usuario
     * 
     * @var string
     */
    public string $password_hash;
    
    /**
     * Indica si el usuario está suscrito al newsletter
     * 
     * @var bool
     */
    public bool $suscrito;
    
    /**
     * Plan del usuario (free o premium)
     * 
     * @var string
     */
    public string $plan;
    
    /**
     * Fecha de registro del usuario
     * 
     * @var string
     */
    public string $fechaRegistro;
    
    /**
     * Constructor del modelo Usuario
     * 
     * Inicializa todas las propiedades del usuario con los valores proporcionados.
     * El constructor es tipado para asegurar la integridad de los datos.
     * 
     * @param string $id ID único del usuario (UUID de Supabase)
     * @param string $nombre Nombre completo del usuario
     * @param string $email Email del usuario
     * @param string $password_hash Hash de la contraseña
     * @param bool $suscrito Estado de suscripción al newsletter
     * @param string $plan Plan del usuario (free o premium)
     * @param string $fechaRegistro Fecha de registro
     */
    public function __construct(
        string $id,
        string $nombre,
        string $email,
        string $password_hash,
        bool $suscrito = false,
        string $plan = 'free',
        string $fechaRegistro = ''
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->suscrito = $suscrito;
        $this->plan = $plan;
        $this->fechaRegistro = $fechaRegistro ?: date('Y-m-d H:i:s');
    }
    
    /**
     * Verifica si el usuario tiene plan premium
     * 
     * @return bool True si tiene plan premium, false en caso contrario
     */
    public function esPremium(): bool {
        return in_array($this->plan, ['premium', 'vip']);
    }
    
    /**
     * Verifica si el usuario está suscrito al newsletter
     * 
     * @return bool True si está suscrito, false en caso contrario
     */
    public function estaSuscrito(): bool {
        return $this->suscrito;
    }
    
    /**
     * Obtiene el nombre legible del plan
     * 
     * @return string Nombre del plan
     */
    public function getNombrePlan(): string {
        $planes = [
            'free' => 'Gratuito',
            'basic' => 'Básico ($5.000)',
            'premium' => 'Premium ($10.000)',
            'vip' => 'VIP ($15.000)'
        ];
        
        return $planes[$this->plan] ?? 'Desconocido';
    }
    
    /**
     * Cambia el plan del usuario
     * 
     * @param string $nuevoPlan Nuevo plan (free o premium)
     * @return bool True si el cambio fue exitoso, false en caso contrario
     */
    public function cambiarPlan(string $nuevoPlan): bool {
        if (in_array($nuevoPlan, ['free', 'premium'])) {
            $this->plan = $nuevoPlan;
            return true;
        }
        return false;
    }
    
    /**
     * Cambia el estado de suscripción del usuario
     * 
     * @param bool $suscrito Nuevo estado de suscripción
     * @return void
     */
    public function cambiarSuscripcion(bool $suscrito): void {
        $this->suscrito = $suscrito;
    }
    
    /**
     * Verifica si una contraseña coincide con el hash almacenado
     * 
     * @param string $password Contraseña a verificar
     * @return bool True si la contraseña es correcta, false en caso contrario
     */
    public function verificarPassword(string $password): bool {
        return password_verify($password, $this->password_hash);
    }
    
    /**
     * Genera un hash seguro para la contraseña
     * 
     * @param string $password Contraseña en texto plano
     * @return string Hash de la contraseña
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Obtiene los datos del usuario como array asociativo
     * 
     * @return array Datos del usuario
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'password_hash' => $this->password_hash,
            'suscrito' => $this->suscrito,
            'plan' => $this->plan,
            'fechaRegistro' => $this->fechaRegistro
        ];
    }
    
    /**
     * Crea una instancia de Usuario desde un array de datos
     * 
     * @param array $data Datos del usuario
     * @return Usuario Instancia del usuario
     */
    public static function fromArray(array $data): Usuario {
        return new Usuario(
            $data['id'] ?? '',
            $data['nombre'] ?? '',
            $data['email'] ?? '',
            $data['password_hash'] ?? '',
            $data['suscrito'] ?? false,
            $data['plan'] ?? 'free',
            $data['fechaRegistro'] ?? date('Y-m-d H:i:s')
        );
    }
    
    /**
     * Obtiene información pública del usuario (sin datos sensibles)
     * 
     * @return array Información pública del usuario
     */
    public function getInfoPublica(): array {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'plan' => $this->plan,
            'fechaRegistro' => $this->fechaRegistro
        ];
    }
    
    /**
     * Valida los datos del usuario
     * 
     * @return array Array de errores de validación (vacío si no hay errores)
     */
    public function validar(): array {
        $errores = [];
        
        // Validar nombre
        if (empty($this->nombre)) {
            $errores['nombre'] = 'El nombre es requerido';
        } elseif (strlen($this->nombre) < 2) {
            $errores['nombre'] = 'El nombre debe tener al menos 2 caracteres';
        } elseif (strlen($this->nombre) > 100) {
            $errores['nombre'] = 'El nombre no puede tener más de 100 caracteres';
        }
        
        // Validar email
        if (empty($this->email)) {
            $errores['email'] = 'El email es requerido';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'El email no es válido';
        }
        
        // Validar plan
        if (!in_array($this->plan, ['free', 'premium'])) {
            $errores['plan'] = 'El plan debe ser "free" o "premium"';
        }
        
        return $errores;
    }
    
    /**
     * Obtiene la edad del usuario en días desde su registro
     * 
     * @return int Edad en días
     */
    public function getEdadEnDias(): int {
        $fechaRegistro = new DateTime($this->fechaRegistro);
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->diff($fechaRegistro);
        return $diferencia->days;
    }
    
    /**
     * Obtiene una representación en string del usuario
     * 
     * @return string Representación del usuario
     */
    public function __toString(): string {
        return "Usuario [ID: {$this->id}, Nombre: {$this->nombre}, Email: {$this->email}, Plan: {$this->plan}]";
    }
}
