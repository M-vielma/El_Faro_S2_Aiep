<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->escape($page_title ?? 'El Faro') ?></title>
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="<?= $this->escape($meta_description ?? 'Periódico digital El Faro - Noticias actuales y confiables') ?>">
    <meta name="keywords" content="<?= $this->escape($meta_keywords ?? 'noticias, periódico, El Faro, actualidad, información') ?>">
    <meta name="author" content="Equipo de Desarrollo El Faro">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        /* Fuentes personalizadas */
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Merriweather', serif;
            font-weight: 700;
        }
        
        /* Colores personalizados */
        .text-purple {
            color: #6f42c1 !important;
        }
        
        .bg-purple {
            background-color: #6f42c1 !important;
        }
        
        /* Navegación mejorada */
        .navbar-brand {
            font-family: 'Merriweather', serif;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.1);
            border-radius: 6px;
        }
        
        /* Cards mejoradas */
        .card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        /* Botones mejorados */
        .btn {
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* Footer mejorado */
        .footer {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: #fff;
        }
        
        .footer a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: #fff;
        }
        
        /* Alertas personalizadas */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        /* Formularios mejorados */
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        /* Responsividad */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.3rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sección de avisos superiores -->
    <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
        <div class="container">
            <i class="bi bi-megaphone me-2"></i>
            <strong>¡Aviso Importante!</strong> Mantente informado con las últimas noticias de <?= $this->escape($app_name) ?>. 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <!-- Encabezado del sitio -->
    <header class="bg-white shadow-sm sticky-top">
        <div class="container">
            <!-- Header principal -->
            <div class="row align-items-center py-3">
                <div class="col-md-4 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                        <img src="<?= image_url('logo.png') ?>?v=<?= time() ?>" alt="Logo El Faro" class="me-3" style="width: 60px; height: 60px; border-radius: 50%;">
                        <h1 class="mb-0 text-primary fw-bold"><?= $this->escape($app_name) ?></h1>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="badge bg-primary fs-6 px-3 py-2" id="fechaHora">
                        <!-- La fecha y hora se actualizará dinámicamente -->
                    </div>
                </div>
                <div class="col-md-4 text-center text-md-end">
                    <div class="d-flex justify-content-center justify-content-md-end gap-2">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Navegación principal -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light rounded mb-3">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav w-100 justify-content-center">
                            <li class="nav-item">
                                <a href="<?= $this->url('/') ?>" class="nav-link <?= $this->isActive('/') ? 'active' : '' ?>">
                                    <i class="bi bi-house-door me-1"></i>Inicio
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $this->url('/articulos') ?>" class="nav-link <?= $this->isActive('/articulos') ? 'active' : '' ?>">
                                    <i class="bi bi-newspaper me-1"></i>Artículos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $this->url('/contacto') ?>" class="nav-link <?= $this->isActive('/contacto') ? 'active' : '' ?>">
                                    <i class="bi bi-envelope me-1"></i>Contacto
                                </a>
                            </li>
                            
                            <?php if (is_user_authenticated()): ?>
                                <!-- Usuario autenticado -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-1"></i>
                                        <?= $this->escape(get_authenticated_user()['nombre']) ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?= $this->url('/perfil') ?>">
                                                <i class="bi bi-person me-2"></i>Mi Perfil
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="<?= $this->url('/logout') ?>">
                                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <!-- Usuario no autenticado -->
                                <li class="nav-item">
                                    <a href="<?= $this->url('/registro') ?>" class="nav-link <?= $this->isActive('/registro') ? 'active' : '' ?>">
                                        <i class="bi bi-person-plus me-1"></i>Registro
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= $this->url('/login') ?>" class="nav-link <?= $this->isActive('/login') ? 'active' : '' ?>">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="container-fluid py-4">
        <div class="container">
            <!-- Mensajes Flash -->
            <?php if (flash_has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>¡Éxito!</strong> <?= $this->escape(flash_get('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (flash_has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Error:</strong> <?= $this->escape(flash_get('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (flash_has('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Información:</strong> <?= $this->escape(flash_get('info')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (flash_has('warning')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Advertencia:</strong> <?= $this->escape(flash_get('warning')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?= $content ?>
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="footer py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <!-- Información de la empresa -->
                <div class="col-lg-3 col-md-6">
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-newspaper text-primary me-2" style="font-size: 2rem;"></i>
                            <h4 class="text-primary mb-0 fw-bold"><?= $this->escape($app_name) ?></h4>
                        </div>
                        <p class="text-light-emphasis">Tu fuente confiable de noticias locales e internacionales. Mantente informado con las últimas noticias del día.</p>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
                

                <!-- Información de contacto -->
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="bi bi-telephone me-2"></i>Contacto
                    </h5>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <span class="text-light-emphasis">info@elfaro.com</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-telephone text-primary me-2"></i>
                            <span class="text-light-emphasis">+56 9 1234 5678</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-geo-alt text-primary me-2"></i>
                            <span class="text-light-emphasis">Santiago, Chile</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <span class="text-light-emphasis">24/7 Noticias</span>
                        </div>
                    </div>
                </div>

                <!-- Información técnica -->
                <div class="col-lg-6 col-md-6">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="bi bi-code-slash me-2"></i>Información Técnica
                    </h5>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-server text-primary me-2"></i>
                            <span class="text-light-emphasis">PHP <?= PHP_VERSION ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-gear text-primary me-2"></i>
                            <span class="text-light-emphasis">Arquitectura MVC</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-bootstrap text-primary me-2"></i>
                            <span class="text-light-emphasis">Bootstrap 5.3.2</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            <span class="text-light-emphasis">Desarrollo Seguro</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Línea divisoria -->
            <hr class="my-4 border-secondary">

            <!-- Información adicional -->
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light-emphasis mb-0">
                        <i class="bi bi-shield-check me-2"></i>
                        &copy; 2025 <?= $this->escape($app_name) ?>. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="#" class="text-light-emphasis text-decoration-none">
                            <i class="bi bi-file-text me-1"></i>Política de Privacidad
                        </a>
                        <a href="#" class="text-light-emphasis text-decoration-none">
                            <i class="bi bi-journal-text me-1"></i>Términos de Uso
                        </a>
                        <a href="#" class="text-light-emphasis text-decoration-none">
                            <i class="bi bi-question-circle me-1"></i>Ayuda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript personalizado -->
    <script>
        // Actualizar fecha y hora en tiempo real
        function actualizarFechaHora() {
            const elementoFechaHora = document.querySelector('.badge.bg-primary');
            if (elementoFechaHora) {
                const ahora = new Date();
                const fechaFormateada = ahora.toLocaleDateString('es-CL', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'America/Santiago'
                });
                elementoFechaHora.innerHTML = '<i class="bi bi-clock me-1"></i>' + fechaFormateada;
            }
        }
        
        // Actualizar cada segundo
        setInterval(actualizarFechaHora, 1000);
        
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Inicializar popovers de Bootstrap
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
</body>
</html>
