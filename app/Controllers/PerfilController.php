<?php
/**
 * Controlador de Perfil para El Faro
 * 
 * Este controlador maneja el perfil del usuario autenticado
 * y la activación de suscripción demo.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class PerfilController extends Controller
{
    /**
     * Muestra el perfil del usuario autenticado
     * 
     * @return void
     */
    public function index(): void
    {
        // Comentamos: Verificamos que el usuario esté autenticado
        if (!AuthController::isUserAuthenticated()) {
            flash_error('Debes iniciar sesión para acceder a tu perfil');
            $this->redirect(base_url() . '/login');
            return;
        }

        $usuario = AuthController::getCurrentUser();
        if (!$usuario) {
            flash_error('Error al cargar los datos del usuario');
            $this->redirect(base_url() . '/login');
            return;
        }

        $this->setTitle('Mi Perfil - El Faro');
        $this->setMetaDescription('Gestiona tu perfil y suscripción en El Faro');
        
        // Comentamos: Pasamos los datos del usuario a la vista
        $this->setData('usuario', $usuario);
        $this->view('perfil/index');
    }

    /**
     * Activa la suscripción premium (demo)
     * 
     * @return void
     */
    public function activarSuscripcion(): void
    {
        // Comentamos: Verificamos que el usuario esté autenticado
        if (!AuthController::isUserAuthenticated()) {
            flash_error('Debes iniciar sesión para activar la suscripción');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Verificamos el token CSRF si es POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf($_POST['csrf_token'] ?? '')) {
                flash_error('Token de seguridad inválido');
                $this->redirect(base_url() . '/perfil');
                return;
            }
        }

        $usuario = AuthController::getCurrentUser();
        if (!$usuario) {
            flash_error('Error al cargar los datos del usuario');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Cargamos el repositorio de usuarios
        $usuarioRepo = new UsuarioRepoMem();
        $usuarioCompleto = $usuarioRepo->find($usuario['id']);

        if (!$usuarioCompleto) {
            flash_error('Usuario no encontrado');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Actualizamos el usuario a premium
        $usuarioCompleto->plan = 'premium';
        $usuarioCompleto->suscrito = true;

        // Comentamos: Actualizamos la sesión
        $_SESSION['auth']['plan'] = 'premium';
        $_SESSION['auth']['suscrito'] = true;

        // Comentamos: En una aplicación real, aquí se guardaría en la base de datos
        // Por ahora solo actualizamos la sesión para la demo

        flash_success('¡Suscripción Premium activada! Ahora tienes acceso completo a El Faro');
        $this->redirect(base_url() . '/perfil');
    }

    /**
     * Desactiva la suscripción (demo)
     * 
     * @return void
     */
    public function desactivarSuscripcion(): void
    {
        // Comentamos: Verificamos que el usuario esté autenticado
        if (!AuthController::isUserAuthenticated()) {
            flash_error('Debes iniciar sesión para gestionar tu suscripción');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url() . '/perfil');
            return;
        }

        $usuario = AuthController::getCurrentUser();
        if (!$usuario) {
            flash_error('Error al cargar los datos del usuario');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Actualizamos el usuario a free
        $_SESSION['auth']['plan'] = 'free';
        $_SESSION['auth']['suscrito'] = false;

        flash_success('Suscripción cancelada. Ahora tienes acceso básico a El Faro');
        $this->redirect(base_url() . '/perfil');
    }

    /**
     * Actualiza los datos del perfil
     * 
     * @return void
     */
    public function actualizar(): void
    {
        // Comentamos: Verificamos que el usuario esté autenticado
        if (!AuthController::isUserAuthenticated()) {
            flash_error('Debes iniciar sesión para actualizar tu perfil');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Verificamos que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/perfil');
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url() . '/perfil');
            return;
        }

        $usuario = AuthController::getCurrentUser();
        if (!$usuario) {
            flash_error('Error al cargar los datos del usuario');
            $this->redirect(base_url() . '/login');
            return;
        }

        // Comentamos: Obtenemos y validamos los datos del formulario
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'newsletter' => isset($_POST['newsletter'])
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
        }

        // Comentamos: Si hay errores, los mostramos y redirigimos
        if (!empty($errors)) {
            foreach ($errors as $error) {
                flash_error($error);
            }
            $this->redirect(base_url() . '/perfil');
            return;
        }

        // Comentamos: Actualizamos la sesión
        $_SESSION['auth']['nombre'] = $data['nombre'];
        $_SESSION['auth']['email'] = $data['email'];
        $_SESSION['auth']['suscrito'] = $data['newsletter'];

        flash_success('Perfil actualizado correctamente');
        $this->redirect(base_url() . '/perfil');
    }

    /**
     * Obtiene las estadísticas del usuario
     * 
     * @return array Estadísticas del usuario
     */
    public static function getEstadisticasUsuario(): array
    {
        if (!AuthController::isUserAuthenticated()) {
            return [];
        }

        $usuario = AuthController::getCurrentUser();
        if (!$usuario) {
            return [];
        }

        return [
            'dias_registrado' => floor((time() - strtotime($usuario['fecha_registro'])) / 86400),
            'plan_actual' => $usuario['plan'],
            'suscrito' => $usuario['suscrito'],
            'beneficios_premium' => [
                'Acceso completo a todos los artículos',
                'Newsletter personalizado',
                'Contenido exclusivo',
                'Sin publicidad'
            ]
        ];
    }
}
