<?php
/**
 * Controlador de Contacto para El Faro
 * 
 * Este controlador maneja el formulario de contacto
 * con validación completa y seguridad CSRF.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class ContactoController extends Controller
{
    /**
     * Muestra el formulario de contacto
     * 
     * @return void
     */
    public function index(): void
    {
        $this->setTitle('Contacto - El Faro');
        $this->setMetaDescription('Ponte en contacto con El Faro - Envíanos tu mensaje');
        
        $this->view('contacto/index');
    }

    /**
     * Procesa el formulario de contacto
     * 
     * @return void
     */
    public function enviar(): void
    {
        // Comentamos: Verificamos que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/contacto');
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url() . '/contacto');
            return;
        }

        // Comentamos: Obtenemos y validamos los datos del formulario
        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'mensaje' => trim($_POST['mensaje'] ?? '')
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

        if (empty($data['mensaje'])) {
            $errors[] = 'El mensaje es requerido';
        } elseif (strlen($data['mensaje']) < 10) {
            $errors[] = 'El mensaje debe tener al menos 10 caracteres';
        } elseif (strlen($data['mensaje']) > 1000) {
            $errors[] = 'El mensaje no puede exceder 1000 caracteres';
        }

        // Comentamos: Si hay errores, los mostramos y redirigimos
        if (!empty($errors)) {
            foreach ($errors as $error) {
                flash_error($error);
            }
            $this->redirect(base_url() . '/contacto');
            return;
        }

        // Comentamos: Simulamos el envío del mensaje (en producción se enviaría por email)
        $this->simularEnvioMensaje($data);

        // Comentamos: Mostramos mensaje de éxito
        flash_success('¡Mensaje enviado correctamente! Te contactaremos pronto.');
        $this->redirect(base_url() . '/contacto');
    }

    /**
     * Simula el envío de un mensaje de contacto
     * 
     * @param array $data Datos del mensaje
     * @return void
     */
    private function simularEnvioMensaje(array $data): void
    {
        // Comentamos: En una aplicación real, aquí se enviaría el email
        // Por ahora solo guardamos en la sesión para demostración
        $mensajes = $_SESSION['mensajes_contacto'] ?? [];
        $mensajes[] = [
            'id' => uniqid(),
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'mensaje' => $data['mensaje'],
            'fecha' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Desconocida'
        ];
        $_SESSION['mensajes_contacto'] = $mensajes;

        // Comentamos: También guardamos en flash para mostrar en la vista
        flash_set('ultimo_mensaje', [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'fecha' => date('d/m/Y H:i:s')
        ]);
    }

    /**
     * Obtiene los mensajes de contacto (para administración)
     * 
     * @return array Lista de mensajes
     */
    public static function getMensajes(): array
    {
        return $_SESSION['mensajes_contacto'] ?? [];
    }

    /**
     * Obtiene el último mensaje enviado
     * 
     * @return array|null Datos del último mensaje o null
     */
    public static function getUltimoMensaje(): ?array
    {
        return flash_get('ultimo_mensaje');
    }
}
