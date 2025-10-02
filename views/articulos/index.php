<?php
/**
 * Vista de listado de artículos para la aplicación El Faro
 * 
 * Esta vista muestra una grilla de artículos con paginación utilizando
 * Bootstrap 5 para el diseño responsivo y reutilizando estilos del sitio original.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */
?>

<!-- Mensaje de desarrollo -->
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-tools me-2 fs-4"></i>
        <div>
            <h5 class="alert-heading mb-1">Sección en Desarrollo</h5>
            <p class="mb-0">Los artículos están en desarrollo. Próximamente tendrás acceso al contenido completo.</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- Sección de encabezado con estadísticas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-primary fw-bold mb-0">
                <i class="bi bi-newspaper me-2"></i>Todos los Artículos
            </h1>
            <div class="d-flex gap-3">
                <span class="badge bg-primary fs-6 px-3 py-2">
                    Total: <?= $this->escape($paginacion['total_articulos'] ?? 0) ?> artículos
                </span>
                <span class="badge bg-success fs-6 px-3 py-2">
                    Página <?= $this->escape($paginacion['pagina_actual'] ?? 1) ?> de <?= $this->escape($paginacion['total_paginas'] ?? 1) ?>
                </span>
            </div>
        </div>
        <hr class="my-3">
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Búsqueda -->
                    <div class="col-md-6">
                        <form method="GET" action="<?= $this->url('/articulos/buscar') ?>" class="d-flex">
                            <input type="text" 
                                   class="form-control" 
                                   name="q" 
                                   placeholder="Buscar artículos..." 
                                   value="<?= $this->escape($_GET['q'] ?? '') ?>">
                            <button type="submit" class="btn btn-primary ms-2">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Filtros por categoría -->
                    <div class="col-md-6">
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="<?= $this->url('/articulos') ?>" 
                               class="btn btn-outline-primary btn-sm">
                                Todas
                            </a>
                            <?php if (isset($categorias) && !empty($categorias)): ?>
                                <?php foreach ($categorias as $categoria): ?>
                                    <a href="<?= $this->url('/articulos/categoria/' . $this->slug($categoria)) ?>" 
                                       class="btn btn-outline-secondary btn-sm">
                                        <?= $this->escape($categoria) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grilla de artículos -->
<?php if (!empty($articulos)): ?>
    <div class="row g-4 mb-5">
        <?php foreach ($articulos as $articulo): ?>
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
                                <a href="<?= $this->url('/articulos/' . $articulo->id) ?>" 
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
    
    <!-- Paginación -->
    <?php if (isset($paginacion) && $paginacion['total_paginas'] > 1): ?>
        <div class="row">
            <div class="col-12">
                <nav aria-label="Paginación de artículos">
                    <ul class="pagination justify-content-center">
                        <!-- Botón anterior -->
                        <?php if ($paginacion['tiene_anterior']): ?>
                            <li class="page-item">
                                <a class="page-link" 
                                   href="<?= $this->url('/articulos?pagina=' . ($paginacion['pagina_actual'] - 1)) ?>">
                                    <i class="bi bi-chevron-left"></i> Anterior
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Números de página -->
                        <?php for ($i = 1; $i <= $paginacion['total_paginas']; $i++): ?>
                            <li class="page-item <?= $i === $paginacion['pagina_actual'] ? 'active' : '' ?>">
                                <a class="page-link" 
                                   href="<?= $this->url('/articulos?pagina=' . $i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Botón siguiente -->
                        <?php if ($paginacion['tiene_siguiente']): ?>
                            <li class="page-item">
                                <a class="page-link" 
                                   href="<?= $this->url('/articulos?pagina=' . ($paginacion['pagina_actual'] + 1)) ?>">
                                    Siguiente <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    <?php endif; ?>
    
<?php else: ?>
    <!-- Mensaje cuando no hay artículos -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-5">
                    <i class="bi bi-newspaper text-muted" style="font-size: 4rem;"></i>
                    <h3 class="text-muted mt-3">No se encontraron artículos</h3>
                    <p class="text-muted">No hay artículos disponibles en este momento.</p>
                    <a href="<?= $this->url('/') ?>" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Estadísticas adicionales -->
<?php if (isset($estadisticas) && !empty($estadisticas)): ?>
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h5 class="text-primary fw-bold mb-3">
                        <i class="bi bi-bar-chart me-2"></i>Estadísticas de Artículos
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
                                <div class="fs-4 fw-bold text-warning"><?= $this->escape($estadisticas['autor_mas_prolifico']) ?></div>
                                <div class="text-muted">Autor Más Prolífico</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- JavaScript para funcionalidad adicional -->
<script>
// Función para búsqueda en tiempo real (opcional)
function buscarArticulos() {
    const termino = document.querySelector('input[name="q"]').value;
    if (termino.length >= 3) {
        // Aquí se podría implementar búsqueda AJAX
        console.log('Buscando:', termino);
    }
}

// Función para filtrar por categoría
function filtrarPorCategoria(categoria) {
    window.location.href = '<?= $this->url('/articulos/categoria/') ?>' + categoria;
}

// Inicializar funcionalidades cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Agregar evento de búsqueda
    const inputBusqueda = document.querySelector('input[name="q"]');
    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', buscarArticulos);
    }
    
    // Agregar efectos hover a las tarjetas
    const tarjetas = document.querySelectorAll('.card');
    tarjetas.forEach(tarjeta => {
        tarjeta.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        tarjeta.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
