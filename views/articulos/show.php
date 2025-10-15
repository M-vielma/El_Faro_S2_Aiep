<?php
/**
 * Vista de detalle de artículo para la aplicación El Faro
 * 
 * Esta vista muestra el mensaje de "Artículo en desarrollo"
 * para todos los artículos individuales.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */
?>

<!-- Mensaje de desarrollo -->
<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-tools me-2 fs-4"></i>
        <div>
            <h5 class="alert-heading mb-1">Artículo en Desarrollo</h5>
            <p class="mb-0">Esta sección está en desarrollo. Próximamente tendrás acceso al contenido completo de los artículos.</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Breadcrumb de navegación -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= $this->url('/') ?>" class="text-decoration-none">
                <i class="bi bi-house me-1"></i>Inicio
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= $this->url('/articulos') ?>" class="text-decoration-none">Artículos</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Artículo en Desarrollo
        </li>
    </ol>
</nav>

<!-- Navegación secuencial de artículos -->
<?php if (isset($articulo_anterior) || isset($articulo_siguiente)): ?>
<div class="navegacion-articulos">
    <!-- Botón Anterior -->
    <?php if (isset($articulo_anterior)): ?>
        <a href="<?= $this->url('/articulos/' . $articulo_anterior->id) ?>" 
           class="boton-navegacion anterior">
            <i class="bi bi-chevron-left"></i>
            <div>
                <div class="small">Artículo Anterior</div>
                <div class="fw-bold"><?= $this->escape(mb_substr($articulo_anterior->titulo, 0, 40) . (mb_strlen($articulo_anterior->titulo) > 40 ? '...' : '')) ?></div>
            </div>
        </a>
    <?php else: ?>
        <span class="boton-navegacion anterior deshabilitado">
            <i class="bi bi-chevron-left"></i>
            <div>
                <div class="small">Sin artículo anterior</div>
            </div>
        </span>
    <?php endif; ?>
    
    <!-- Información de posición -->
    <div class="info-navegacion">
        <strong>Artículo <?= $this->escape($posicion_actual ?? 1) ?> de <?= $this->escape($total_articulos ?? 1) ?></strong>
    </div>
    
    <!-- Botón Siguiente -->
    <?php if (isset($articulo_siguiente)): ?>
        <a href="<?= $this->url('/articulos/' . $articulo_siguiente->id) ?>" 
           class="boton-navegacion siguiente">
            <div>
                <div class="small">Artículo Siguiente</div>
                <div class="fw-bold"><?= $this->escape(mb_substr($articulo_siguiente->titulo, 0, 40) . (mb_strlen($articulo_siguiente->titulo) > 40 ? '...' : '')) ?></div>
            </div>
            <i class="bi bi-chevron-right"></i>
        </a>
    <?php else: ?>
        <span class="boton-navegacion siguiente deshabilitado">
            <div>
                <div class="small">Sin artículo siguiente</div>
            </div>
            <i class="bi bi-chevron-right"></i>
        </span>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Contenido principal -->
<div class="row">
    <!-- Mensaje principal -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-tools text-primary" style="font-size: 4rem;"></i>
                </div>
                <h1 class="display-4 text-primary fw-bold mb-3">Artículo en Desarrollo</h1>
                <p class="lead text-muted mb-4">
                    Estamos trabajando para traerte el mejor contenido. 
                    Próximamente podrás acceder a artículos completos con información detallada.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="<?= $this->url('/articulos') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-newspaper me-2"></i>Ver Todos los Artículos
                    </a>
                    <a href="<?= $this->url('/') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-house me-2"></i>Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h5 class="text-primary fw-bold mb-3">
                    <i class="bi bi-info-circle me-2"></i>¿Qué puedes hacer mientras tanto?
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-newspaper text-primary me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-1">Explorar Artículos</h6>
                                <p class="text-muted small mb-0">Descubre nuestro catálogo completo</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-info me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-1">Contactar</h6>
                                <p class="text-muted small mb-0">Envía tus sugerencias o consultas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-plus text-success me-3" style="font-size: 2rem;"></i>
                            <div>
                                <h6 class="mb-1">Registrarse</h6>
                                <p class="text-muted small mb-0">Únete a nuestra comunidad</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>