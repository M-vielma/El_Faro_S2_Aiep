<?php
/**
 * Controlador de Autenticación para El Faro
 * 
 * Este controlador maneja el registro, login y logout de usuarios
 * con validación completa y seguridad CSRF.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Cargar dependencias necesarias
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Repositorios/UsuarioRepoMem.php';

class AuthController extends Controller
{
    /**
     * Muestra el formulario de registro
     * 
     * @return void
     */
    public function registro(): void
    {
        // Comentamos: Verificamos si el usuario ya está autenticado
        if ($this->isAuthenticated()) {
            $this->redirect(base_url() . '/perfil');
            return;
        }

        $this->setTitle('Registro - El Faro');
        $this->setMetaDescription('Regístrate en El Faro para acceder a contenido exclusivo');
        
        $this->view('auth/registro');
    }

    /**
     * Procesa el formulario de registro
     * 
     * @return void
     */
    public function procesarRegistro(): void
    {
        // Comentamos: Verificamos que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/registro');
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url() . '/registro');
            return;
        }

        // Cargar repositorio de usuarios (asegurar que las clases estén disponibles)
        if (!class_exists('UsuarioRepoMem')) {
            require_once __DIR__ . '/../Models/Repositorios/UsuarioRepoMem.php';
        }
        $usuarioRepo = new UsuarioRepoMem();

        // Comentamos: Obtenemos y validamos los datos del formulario
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'plan' => $_POST['plan'] ?? '10000'
        ];

        $errors = [];

        // Validaciones
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        } elseif (strlen($data['nombre']) < 2) {
            $errors[] = 'El nombre debe tener al menos 2 caracteres';
        }

        if (empty($data['email'])) {
            $errors[] = 'El correo electrónico es requerido';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico no es válido';
        } elseif ($usuarioRepo->findByEmail($data['email'])) {
            $errors[] = 'Este correo electrónico ya está registrado';
        }

        if (empty($data['password'])) {
            $errors[] = 'La contraseña es requerida';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }

        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'Las contraseñas no coinciden';
        }

        // Comentamos: Si hay errores, los mostramos y redirigimos
        if (!empty($errors)) {
            foreach ($errors as $error) {
                flash_error($error);
            }
            $this->redirect(base_url() . '/registro');
            return;
        }

        // Comentamos: Determinamos el plan basado en el precio seleccionado
        $plan = 'free';
        if ($data['plan'] == '5000') {
            $plan = 'basic';
        } elseif ($data['plan'] == '10000') {
            $plan = 'premium';
        } elseif ($data['plan'] == '15000') {
            $plan = 'vip';
        }

        // Comentamos: Creamos el nuevo usuario
        $usuario = new Usuario(
            0, // ID será asignado automáticamente
            $data['nombre'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            true, // Suscrito al plan seleccionado
            $plan, // Plan seleccionado
            date('Y-m-d H:i:s')
        );

        // Comentamos: Guardamos el usuario en el repositorio
        $usuarioCreado = $usuarioRepo->create($usuario);

        if ($usuarioCreado) {
            // Comentamos: Registro exitoso, mostrar formulario de login
            flash_success('¡Registro exitoso! Ahora puedes iniciar sesión');
            $this->view('auth/login', [
                'email' => $data['email'], // Pre-llenar el email
                'show_registration_success' => true
            ]);
        } else {
            flash_error('Error al crear la cuenta. Inténtalo de nuevo.');
            $this->view('auth/registro');
        }
    }

    /**
     * Muestra el formulario de login
     * 
     * @return void
     */
    public function login(): void
    {
        // Comentamos: Verificamos si el usuario ya está autenticado
        if ($this->isAuthenticated()) {
            $this->redirect(base_url() . '/perfil');
            return;
        }

        $this->setTitle('Iniciar Sesión - El Faro');
        $this->setMetaDescription('Inicia sesión en tu cuenta de El Faro');
        
        $this->view('auth/login');
    }

    /**
     * Procesa el formulario de login
     * 
     * @return void
     */
    public function procesarLogin(): void
    {
        // Comentamos: Verificamos que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Cargar repositorio de usuarios (asegurar que las clases estén disponibles)
        if (!class_exists('UsuarioRepoMem')) {
            require_once __DIR__ . '/../Models/Repositorios/UsuarioRepoMem.php';
        }
        $usuarioRepo = new UsuarioRepoMem();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas
        if (empty($email) || empty($password)) {
            flash_error('Email y contraseña son requeridos');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Buscamos el usuario por email
        $usuario = $usuarioRepo->findByEmail($email);

        if (!$usuario || !password_verify($password, $usuario->password_hash)) {
            flash_error('Credenciales incorrectas');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Iniciamos sesión del usuario
        $this->loginUser($usuario);
        flash_success('¡Bienvenido de vuelta!');
        $this->redirect(base_url());
    }

    /**
     * Cierra la sesión del usuario
     * 
     * @return void
     */
    public function logout(): void
    {
        // Comentamos: Limpiamos la sesión de autenticación
        unset($_SESSION['auth']);
        flash_success('Has cerrado sesión correctamente');
        $this->redirect(base_url());
    }

    /**
     * Verifica si el usuario está autenticado
     * 
     * @return bool
     */
    private function isAuthenticated(): bool
    {
        return isset($_SESSION['auth']) && !empty($_SESSION['auth']);
    }

    /**
     * Inicia sesión del usuario
     * 
     * @param Usuario $usuario Usuario a autenticar
     * @return void
     */
    private function loginUser(Usuario $usuario): void
    {
        // Comentamos: Guardamos los datos del usuario en la sesión
        $_SESSION['auth'] = [
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'email' => $usuario->email,
            'plan' => $usuario->plan,
            'suscrito' => $usuario->suscrito,
            'fecha_registro' => $usuario->fechaRegistro
        ];
    }

    /**
     * Obtiene el usuario autenticado actual
     * 
     * @return array|null Datos del usuario o null si no está autenticado
     */
    public static function getCurrentUser(): ?array
    {
        return $_SESSION['auth'] ?? null;
    }

    /**
     * Verifica si el usuario actual está autenticado
     * 
     * @return bool
     */
    public static function isUserAuthenticated(): bool
    {
        return isset($_SESSION['auth']) && !empty($_SESSION['auth']);
    }
}
