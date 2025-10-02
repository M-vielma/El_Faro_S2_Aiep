<?php
/**
 * Controlador principal para la página de inicio de El Faro
 * 
 * Este controlador maneja las rutas principales de la aplicación,
 * incluyendo la página de inicio, artículos y formularios de contacto.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class HomeController extends Controller {
    
    /**
     * Página de inicio
     * 
     * Muestra la página principal con artículos recientes destacados
     * y tarjetas de los últimos artículos publicados
     */
    public function index() {
        // Cargar repositorio de artículos
        require_once __DIR__ . '/../Models/Repositorios/ArticuloRepoMem.php';
        $articuloRepo = new ArticuloRepoMem();
        
        // Establecer título y metadatos de la página
        $this->setTitle('Inicio');
        $this->setMetaDescription('Bienvenidos a El Faro - Periódico digital con las últimas noticias y actualidad');
        $this->setMetaKeywords('noticias, El Faro, actualidad, periódico digital, información');
        
        // Obtener artículos recientes (3-4 artículos más recientes)
        $articulosRecientes = $articuloRepo->allOrderedByDate();
        $articulosDestacados = array_slice($articulosRecientes, 0, 4);
        
        // Obtener el artículo más reciente como destacado
        $articuloDestacado = !empty($articulosDestacados) ? $articulosDestacados[0] : null;
        
        // Obtener artículos secundarios (resto de artículos)
        $articulosSecundarios = array_slice($articulosDestacados, 1, 3);
        
        // Obtener estadísticas de artículos
        $estadisticas = $articuloRepo->getEstadisticas();
        
        // Obtener categorías para navegación
        $categorias = $articuloRepo->getCategorias();
        
        // Cargar configuración con fallback seguro
        $configPath = __DIR__ . '/../../config/app.php';
        $config = is_file($configPath) ? include $configPath : null;
        
        if (!is_array($config)) {
            // Fallback para no romper la app si falla la carga
            $config = [
                'app_name' => 'El Faro',
                'base_url' => '/',
                'ui' => 'bootstrap',
            ];
        }
        
        // Datos adicionales para la vista (usando las CLAVES correctas)
        $this->setData('app_name', $config['app_name'] ?? 'El Faro');
        $this->setData('welcome_message', 'Bienvenidos a El Faro (prototipo MVC)');
        $this->setData('current_time', date('d/m/Y H:i:s'));
        $this->setData('articulo_destacado', $articuloDestacado);
        $this->setData('articulos_secundarios', $articulosSecundarios);
        $this->setData('estadisticas', $estadisticas);
        $this->setData('categorias', $categorias);
        $this->setData('server_info', [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Desconocido'
        ]);
        
        // Renderizar la vista
        $this->view('home/index');
    }
    
    /**
     * Página de artículos
     * 
     * Muestra una lista de artículos disponibles
     */
    public function articulos() {
        $this->setTitle('Artículos');
        $this->setMetaDescription('Lista de artículos y noticias de El Faro');
        
        // Datos de ejemplo para los artículos
        $this->setData('articulos', [
            [
                'id' => 1,
                'titulo' => 'Noticia de Ejemplo 1',
                'descripcion' => 'Esta es una noticia de ejemplo para demostrar el funcionamiento del sistema MVC.',
                'fecha' => date('d/m/Y'),
                'categoria' => 'General'
            ],
            [
                'id' => 2,
                'titulo' => 'Noticia de Ejemplo 2',
                'descripcion' => 'Otra noticia de ejemplo que muestra la funcionalidad del sistema.',
                'fecha' => date('d/m/Y'),
                'categoria' => 'Tecnología'
            ]
        ]);
        
        $this->view('home/articulos');
    }
    
    /**
     * Página de contacto
     * 
     * Muestra el formulario de contacto
     */
    public function contacto() {
        $this->setTitle('Contacto');
        $this->setMetaDescription('Página de contacto de El Faro - Envíanos tu mensaje');
        
        $this->view('home/contacto');
    }
    
    /**
     * Procesa el formulario de contacto
     * 
     * Maneja el envío del formulario de contacto
     */
    public function enviarContacto() {
        // Verificar que sea una petición POST
        if (!$this->isAjax() && $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/contacto');
            return;
        }
        
        // Validar datos
        $data = [
            'nombre' => post_param('nombre', ''),
            'email' => post_param('email', ''),
            'mensaje' => post_param('mensaje', '')
        ];
        
        $rules = [
            'nombre' => 'required|min:2|max:100',
            'email' => 'required|email',
            'mensaje' => 'required|min:10|max:1000'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'errors' => $errors], 400);
            } else {
                $this->setData('errors', $errors);
                $this->setData('form_data', $data);
                $this->view('home/contacto');
            }
            return;
        }
        
        // Simular envío del mensaje (en una aplicación real, aquí se guardaría en BD)
        $this->setSession('contacto_enviado', true);
        $this->setSession('contacto_mensaje', '¡Gracias por tu mensaje! Te contactaremos pronto.');
        
        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => 'Mensaje enviado correctamente']);
        } else {
            $this->redirect(base_url() . '/contacto');
        }
    }
    
    /**
     * Página de registro
     * 
     * Muestra el formulario de registro
     */
    public function registro() {
        $this->setTitle('Registro');
        $this->setMetaDescription('Regístrate en El Faro para recibir las últimas noticias');
        
        $this->view('auth/register');
    }
    
    /**
     * Procesa el formulario de registro
     * 
     * Maneja el registro de nuevos usuarios
     */
    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/registro');
            return;
        }
        
        // Cargar repositorio de usuarios
        require_once __DIR__ . '/../Models/Usuario.php';
        require_once __DIR__ . '/../Models/Repositorios/UsuarioRepoMem.php';
        $usuarioRepo = new UsuarioRepoMem();
        
        // Validar datos del registro
        $data = [
            'nombre' => post_param('nombre', ''),
            'email' => post_param('email', ''),
            'password' => post_param('password', ''),
            'password_confirm' => post_param('password_confirm', ''),
            'plan' => post_param('plan', 'free'),
            'suscrito' => post_param('suscrito', false),
            'terminos' => post_param('terminos', false)
        ];
        
        $errors = [];
        
        // Validaciones básicas
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El correo electrónico es requerido y debe ser válido';
        }
        
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'La contraseña debe tener al menos 6 caracteres';
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $errors[] = 'Las contraseñas no coinciden';
        }
        
        if (!$data['terminos']) {
            $errors[] = 'Debes aceptar los términos y condiciones';
        }
        
        // Verificar si el email ya existe
        if (empty($errors) && $usuarioRepo->findByEmail($data['email'])) {
            $errors[] = 'Este correo electrónico ya está registrado';
        }
        
        if (!empty($errors)) {
            $this->setData('errors', $errors);
            $this->view('auth/register');
            return;
        }
        
        // Crear nuevo usuario
        $usuario = new Usuario(
            0, // ID será asignado automáticamente
            $data['nombre'],
            $data['email'],
            Usuario::hashPassword($data['password']),
            (bool)$data['suscrito'],
            $data['plan'],
            date('Y-m-d H:i:s')
        );
        
        // Guardar usuario
        $usuarioCreado = $usuarioRepo->create($usuario);
        
        if ($usuarioCreado) {
            $this->setSession('registro_exitoso', true);
            $this->setSession('usuario_nombre', $usuarioCreado->nombre);
            $this->setSession('usuario_id', $usuarioCreado->id);
            $this->redirect(base_url());
        } else {
            $errors[] = 'Error al crear la cuenta. Inténtalo de nuevo.';
            $this->setData('errors', $errors);
            $this->view('auth/register');
        }
    }
    
    /**
     * Página de login
     * 
     * Muestra el formulario de inicio de sesión
     */
    public function login() {
        $this->setTitle('Iniciar Sesión');
        $this->setMetaDescription('Inicia sesión en El Faro');
        
        $this->view('auth/login');
    }
    
    /**
     * Procesa el formulario de login
     * 
     * Maneja el inicio de sesión de usuarios
     */
    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(base_url() . '/login');
            return;
        }
        
        // Cargar repositorio de usuarios
        require_once __DIR__ . '/../Models/Usuario.php';
        require_once __DIR__ . '/../Models/Repositorios/UsuarioRepoMem.php';
        $usuarioRepo = new UsuarioRepoMem();
        
        $email = post_param('email', '');
        $password = post_param('password', '');
        $remember = post_param('remember', false);
        
        // Validación básica
        if (empty($email) || empty($password)) {
            $this->setData('error', 'Email y contraseña son requeridos');
            $this->view('auth/login');
            return;
        }
        
        // Buscar usuario por email
        $usuario = $usuarioRepo->findByEmail($email);
        
        if (!$usuario) {
            $this->setData('error', 'Credenciales incorrectas');
            $this->view('auth/login');
            return;
        }
        
        // Verificar contraseña
        if (!$usuario->verificarPassword($password)) {
            $this->setData('error', 'Credenciales incorrectas');
            $this->view('auth/login');
            return;
        }
        
        // Login exitoso
        $this->setSession('usuario_autenticado', true);
        $this->setSession('usuario_id', $usuario->id);
        $this->setSession('usuario_nombre', $usuario->nombre);
        $this->setSession('usuario_email', $usuario->email);
        $this->setSession('usuario_plan', $usuario->plan);
        
        // Si marcó "recordarme", extender la sesión
        if ($remember) {
            ini_set('session.cookie_lifetime', 86400 * 30); // 30 días
        }
        
        $this->redirect(base_url());
    }
    
    /**
     * Muestra un artículo específico
     * 
     * @param int $id ID del artículo
     */
    public function articulo($id) {
        $this->setTitle('Artículo');
        
        // Simular datos del artículo
        $articulo = [
            'id' => $id,
            'titulo' => 'Artículo de Ejemplo ' . $id,
            'contenido' => 'Este es el contenido completo del artículo número ' . $id . '. Aquí se mostraría el texto completo de la noticia.',
            'fecha' => date('d/m/Y'),
            'autor' => 'Redacción El Faro'
        ];
        
        $this->setData('articulo', $articulo);
        $this->view('home/articulo');
    }
    
    /**
     * Muestra artículos por categoría
     * 
     * @param string $categoria Nombre de la categoría
     */
    public function categoria($categoria) {
        $this->setTitle('Categoría: ' . ucfirst($categoria));
        
        // Simular artículos por categoría
        $articulos = [
            [
                'id' => 1,
                'titulo' => 'Artículo de ' . $categoria . ' 1',
                'descripcion' => 'Descripción del artículo de la categoría ' . $categoria,
                'fecha' => date('d/m/Y')
            ],
            [
                'id' => 2,
                'titulo' => 'Artículo de ' . $categoria . ' 2',
                'descripcion' => 'Otro artículo de la categoría ' . $categoria,
                'fecha' => date('d/m/Y')
            ]
        ];
        
        $this->setData('categoria', $categoria);
        $this->setData('articulos', $articulos);
        $this->view('home/categoria');
    }
}
