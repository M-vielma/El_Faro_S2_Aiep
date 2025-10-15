<?php
/**
 * Controlador de Autenticación para El Faro
 * 
 * Este controlador maneja el registro, login y logout de usuarios
 * con validación completa y seguridad CSRF.
 * Utiliza Supabase para autenticación y persistencia de datos.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

// Cargar dependencias necesarias
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Repositorios/UsuarioRepoSupabase.php';
require_once __DIR__ . '/../Services/SupabaseService.php';

use ElFaro\Services\SupabaseService;

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
            $this->redirect(base_url('perfil'));
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
            $this->redirect(base_url('registro'));
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url('registro'));
            return;
        }

        // Cargar repositorio de usuarios con Supabase
        $usuarioRepo = new UsuarioRepoSupabase();

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
            $this->redirect(base_url('registro'));
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

        try {
            // Comentamos: Creamos el nuevo usuario en Supabase Auth
            $auth = SupabaseService::auth();
            $authResponse = $auth->signUp([
                'email' => $data['email'],
                'password' => $data['password'],
                'data' => [
                    'nombre' => $data['nombre'],
                    'plan' => $plan
                ]
            ]);

            // Verificar si hubo error
            if (isset($authResponse['error']) && $authResponse['error'] !== null) {
                throw new \Exception($authResponse['error']->getMessage());
            }

            // Verificar si se creó el usuario en Auth
            if (!isset($authResponse['data']['user'])) {
                throw new \Exception('No se pudo crear el usuario en Supabase Auth');
            }

            $userData = $authResponse['data']['user'];
            
            // Obtener el ID del usuario de Auth
            $userId = $userData['id'] ?? null;
            
            // Si no hay ID, el usuario se creó pero necesita confirmar su email
            // En este caso, simplemente redirigimos a login
            if (empty($userId)) {
                flash_success('¡Registro exitoso! Por favor, revisa tu email para confirmar tu cuenta.');
                $this->redirect('/login');
                return;
            }
            
            // Comentamos: Guardamos el usuario en la tabla usuarios
            $usuario = new Usuario(
                $userId, // ID del usuario de Auth
                $data['nombre'],
                $data['email'],
                '', // El hash se maneja en Supabase Auth
                true, // Suscrito al plan seleccionado
                $plan, // Plan seleccionado
                date('Y-m-d H:i:s')
            );

            // Intentar crear el usuario en la tabla usuarios
            try {
                $usuarioCreado = $usuarioRepo->create($usuario);
                
                if ($usuarioCreado) {
                    // Comentamos: Registro exitoso, redirigir a login
                    flash_success('¡Registro exitoso! Ahora puedes iniciar sesión');
                    $this->redirect('/login');
                } else {
                    flash_error('Error al crear la cuenta. Inténtalo de nuevo.');
                    $this->redirect('/registro');
                }
            } catch (\Exception $e) {
                // Si falla al crear en la tabla usuarios, pero ya se creó en Auth,
                // consideramos el registro exitoso de todos modos
                flash_success('¡Registro exitoso! Ahora puedes iniciar sesión');
                $this->redirect('/login');
            }
        } catch (\Exception $e) {
            flash_error('Error al crear la cuenta: ' . $e->getMessage());
            $this->redirect('/registro');
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
            $this->redirect(base_url('perfil'));
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
            $this->redirect(base_url('login'));
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url('login'));
            return;
        }

        // Cargar repositorio de usuarios con Supabase
        $usuarioRepo = new UsuarioRepoSupabase();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validaciones básicas
        if (empty($email) || empty($password)) {
            flash_error('Email y contraseña son requeridos');
            $this->redirect(base_url('login'));
            return;
        }

        try {
            // Comentamos: Autenticamos el usuario con Supabase Auth
            $auth = SupabaseService::auth();
            $authResponse = $auth->signInWithPassword([
                'email' => $email,
                'password' => $password
            ]);

            // Verificar si hubo error
            if (isset($authResponse['error']) && $authResponse['error'] !== null) {
                throw new \Exception($authResponse['error']->getMessage());
            }

            // Verificar si se autenticó correctamente
            if (isset($authResponse['data']['user'])) {
                $userData = $authResponse['data']['user'];
                
                // Comentamos: Obtenemos el usuario de la base de datos
                $usuario = $usuarioRepo->findById($userData['id']);

                // Si no existe en la tabla usuarios, crearlo
                if (!$usuario) {
                    $usuario = new Usuario(
                        $userData['id'],
                        $userData['user_metadata']['nombre'] ?? 'Usuario',
                        $userData['email'],
                        '', // El hash se maneja en Supabase
                        true,
                        $userData['user_metadata']['plan'] ?? 'free',
                        date('Y-m-d H:i:s')
                    );
                    
                    try {
                        $usuarioRepo->create($usuario);
                    } catch (\Exception $e) {
                        // Si falla, continuar de todos modos
                        error_log('Error al crear usuario en tabla: ' . $e->getMessage());
                    }
                }

                // Comentamos: Iniciamos sesión del usuario
                $this->loginUser($usuario);
                flash_success('¡Bienvenido de vuelta!');
                $this->redirect(base_url());
            } else {
                flash_error('Credenciales incorrectas');
                $this->redirect(base_url('login'));
            }
        } catch (\Exception $e) {
            flash_error('Error al iniciar sesión: ' . $e->getMessage());
            $this->redirect(base_url('login'));
        }
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
