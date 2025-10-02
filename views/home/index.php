<?php
/**
 * Vista de inicio para la aplicación El Faro
 * 
 * Esta vista muestra la página principal con bloque de bienvenida
 * y artículos destacados.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */
?>

<!-- Bloque de Bienvenida -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-primary shadow-lg">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">
                        <i class="bi bi-house-door me-2"></i>Bienvenidos a El Faro
                    </h2>
                    <span class="badge bg-light text-primary fs-6">
                        <i class="bi bi-check-circle me-1"></i>MVC Funcionando
                    </span>
                </div>
            </div>
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="display-4 text-primary fw-bold mb-3">
                            Bienvenidos a El Faro (prototipo MVC)
                        </h1>
                        <p class="lead text-muted mb-4">
                            Tu fuente confiable de noticias e información actualizada. 
                            Mantente informado con nuestros artículos de calidad y análisis profundos.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="<?= $this->url('/articulos') ?>" class="btn btn-primary btn-lg">
                                <i class="bi bi-newspaper me-2"></i>Explorar Artículos
                            </a>
                            <a href="<?= $this->url('/contacto') ?>" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-envelope me-2"></i>Contacto
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-light rounded-3 p-4">
                            <img src="<?= image_url('logo.png') ?>" 
                                 alt="Logo El Faro" 
                                 class="img-fluid mb-3" 
                                 style="max-height: 100px;">
                            <h5 class="text-primary">Noticias Actualizadas</h5>
                            <p class="text-muted small">Contenido fresco y relevante todos los días</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jumbotron principal con artículo destacado -->
<?php if (isset($articulo_destacado) && $articulo_destacado): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 shadow-lg overflow-hidden">
            <!-- Imagen destacada -->
            <div class="position-relative" style="height: 400px; overflow: hidden;">
                <img src="<?= image_url($articulo_destacado->imagen) ?>" 
                     class="w-100 h-100" 
                     alt="<?= $this->escape($articulo_destacado->titulo) ?>"
                     style="object-fit: cover;">
                <div class="position-absolute top-0 start-0 w-100 h-100" 
                     style="background: linear-gradient(45deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);"></div>
                <div class="position-absolute top-0 start-0 m-4">
                    <span class="badge bg-primary fs-6 px-3 py-2">DESTACADO</span>
                </div>
                <div class="position-absolute top-0 end-0 m-4">
                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                        <?= $this->escape($articulo_destacado->categoria) ?>
                    </span>
                </div>
            </div>
            
            <!-- Contenido del jumbotron -->
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="display-4 text-primary fw-bold mb-3">
                            <?= $this->escape($articulo_destacado->titulo) ?>
                        </h1>
                        <p class="lead text-muted mb-4">
                            <?= $this->escape($articulo_destacado->bajada) ?>
                        </p>
                        <div class="d-flex align-items-center mb-4">
                            <div class="d-flex align-items-center me-4">
                                <i class="bi bi-person text-primary me-2"></i>
                                <span class="fw-bold"><?= $this->escape($articulo_destacado->autor) ?></span>
                            </div>
                            <div class="d-flex align-items-center me-4">
                                <i class="bi bi-calendar text-primary me-2"></i>
                                <span><?= $this->escape($articulo_destacado->getFechaFormateada('d \d\e F \d\e Y')) ?></span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2"></i>
                                <span><?= $this->escape($articulo_destacado->getTiempoLectura()) ?> min</span>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="<?= $this->url('/articulos') ?>" 
                               class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-right me-2"></i>Leer Artículo Completo
                            </a>
                            <a href="<?= $this->url('/articulos') ?>" class="btn btn-outline-primary btn-lg">
                                <i class="bi bi-newspaper me-2"></i>Ver Todos los Artículos
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="bg-light rounded-3 p-4">
                            <i class="bi bi-star-fill text-warning" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-primary">Artículo Destacado</h5>
                            <p class="text-muted small">Nuestra selección editorial del día</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Sección Multimedia -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h3 class="card-title mb-0">
                    <i class="bi bi-play-circle me-2"></i>Video Destacado
                </h3>
            </div>
            <div class="card-body">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/f_-XZZTZeJ0" title="Video de noticias destacado" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h3 class="card-title mb-0">
                    <i class="bi bi-volume-up me-2"></i>Audio Destacado
                </h3>
            </div>
            <div class="card-body">
                <audio controls class="w-100">
                    <source src="https://www.soundjay.com/misc/sounds/bell-ringing-05.wav" type="audio/wav">
                    <source src="https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3" type="audio/mpeg">
                    Tu navegador no soporta el elemento de audio.
                </audio>
            </div>
        </div>
    </div>
</div>

<!-- Mensaje de éxito si hay uno en la sesión -->
<?php if (isset($_SESSION['contacto_enviado']) && $_SESSION['contacto_enviado']): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>¡Éxito!</strong> <?= $this->escape($_SESSION['contacto_mensaje'] ?? 'Mensaje enviado correctamente') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php 
    // Limpiar el mensaje de la sesión
    unset($_SESSION['contacto_enviado']);
    unset($_SESSION['contacto_mensaje']);
    ?>
<?php endif; ?>

<!-- Mensaje de registro exitoso -->
<?php if (isset($_SESSION['registro_exitoso']) && $_SESSION['registro_exitoso']): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-person-check me-2"></i>
                <strong>¡Registro Exitoso!</strong> Bienvenido <?= $this->escape($_SESSION['usuario_nombre'] ?? 'Usuario') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php 
    // Limpiar el mensaje de la sesión
    unset($_SESSION['registro_exitoso']);
    ?>
<?php endif; ?>

<!-- Sección de artículos recientes -->
<?php if (isset($articulos_secundarios) && !empty($articulos_secundarios)): ?>
<div class="row mb-5">
    <div class="col-12">
        <h3 class="text-primary fw-bold mb-4">
            <i class="bi bi-clock-history me-2"></i>Artículos Recientes
        </h3>
    </div>
</div>

<div class="row g-4 mb-5">
    <?php foreach ($articulos_secundarios as $articulo): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <!-- Imagen del artículo -->
                <div class="card-img-top-container position-relative">
                    <img src="<?= image_url($articulo->imagen) ?>" 
                         class="card-img-top" 
                         alt="<?= $this->escape($articulo->titulo) ?>"
                         style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-primary"><?= $this->escape($articulo->categoria) ?></span>
                    </div>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-light text-dark">
                            <?= $this->escape($articulo->getEdadLegible()) ?>
                        </span>
                    </div>
                </div>
                
                <!-- Contenido del artículo -->
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-primary fw-bold">
                        <a href="<?= $this->url('/articulos/' . $articulo->id) ?>" 
                           class="text-decoration-none">
                            <?= $this->escape($articulo->titulo) ?>
                        </a>
                    </h5>
                    
                    <p class="card-text flex-grow-1 text-muted">
                        <?= $this->escape($articulo->getResumen(120)) ?>
                    </p>
                    
                    <!-- Metadatos del artículo -->
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>
                                <?= $this->escape($articulo->autor) ?>
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                <?= $this->escape($articulo->getFechaFormateada('d/m/Y')) ?>
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-clock-history me-1"></i>
                                <?= $this->escape($articulo->getTiempoLectura()) ?> min lectura
                            </small>
                            <a href="<?= $this->url('/articulos') ?>" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-right me-1"></i>Leer más
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Botón para ver más artículos -->
<div class="row">
    <div class="col-12 text-center">
        <a href="<?= $this->url('/articulos') ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-newspaper me-2"></i>Ver Todos los Artículos
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Estadísticas de artículos -->
<?php if (isset($estadisticas) && !empty($estadisticas)): ?>
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h5 class="text-primary fw-bold mb-3">
                    <i class="bi bi-bar-chart me-2"></i>Estadísticas de El Faro
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-primary"><?= $this->escape($estadisticas['total']) ?></div>
                            <div class="text-muted">Total de Artículos</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-success"><?= $this->escape($estadisticas['recientes']) ?></div>
                            <div class="text-muted">Artículos Recientes</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-info"><?= $this->escape($estadisticas['categoria_mas_comun']) ?></div>
                            <div class="text-muted">Categoría Más Común</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-warning">Miguel Vielma</div>
                            <div class="text-muted">Autor Más Prolífico</div>
                        </div>
                    </div>
                </div>
                
                <!-- Distribución por categorías -->
                <?php if (isset($estadisticas['categorias']) && !empty($estadisticas['categorias'])): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-primary fw-bold mb-3">Distribución por Categorías</h6>
                        <div class="row g-2">
                            <?php foreach ($estadisticas['categorias'] as $categoria => $cantidad): ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded">
                                        <span class="fw-bold"><?= $this->escape($categoria) ?></span>
                                        <span class="badge bg-primary"><?= $cantidad ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>