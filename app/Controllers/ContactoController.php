<?php
/**
 * Controlador de Contacto para El Faro
 * 
 * Este controlador maneja el formulario de contacto
 * con validación completa y seguridad CSRF.
 * Utiliza Supabase para persistencia de datos.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

require_once __DIR__ . '/../Models/Repositorios/ContactoRepoSupabase.php';

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
            $this->redirect(base_url('contacto'));
            return;
        }

        // Comentamos: Verificamos el token CSRF
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash_error('Token de seguridad inválido');
            $this->redirect(base_url('contacto'));
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
            $this->redirect(base_url('contacto'));
            return;
        }

        try {
            // Comentamos: Guardamos el mensaje en Supabase
            $contactoRepo = new ContactoRepoSupabase();
            $mensajeCreado = $contactoRepo->create($data);

            if ($mensajeCreado) {
                // Comentamos: Mostramos mensaje de éxito y redirigimos al inicio
                flash_success('¡Mensaje enviado correctamente! Te contactaremos pronto.');
                // Debug temporal
                error_log('Redirigiendo a: ' . base_url());
                $this->redirect('/');
            } else {
                flash_error('Error al enviar el mensaje. Inténtalo de nuevo.');
                $this->redirect('/contacto');
            }
        } catch (\Exception $e) {
            flash_error('Error al enviar el mensaje: ' . $e->getMessage());
            $this->redirect(base_url('contacto'));
        }
    }

    /**
     * Obtiene los mensajes de contacto (para administración)
     * 
     * @return array Lista de mensajes
     */
    public static function getMensajes(): array
    {
        try {
            $contactoRepo = new ContactoRepoSupabase();
            return $contactoRepo->all();
        } catch (\Exception $e) {
            error_log('Error al obtener mensajes: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtiene el último mensaje enviado
     * 
     * @return array|null Datos del último mensaje o null
     */
    public static function getUltimoMensaje(): ?array
    {
        try {
            $contactoRepo = new ContactoRepoSupabase();
            $mensajes = $contactoRepo->all();
            return !empty($mensajes) ? $mensajes[0] : null;
        } catch (\Exception $e) {
            error_log('Error al obtener último mensaje: ' . $e->getMessage());
            return null;
        }
    }
}
