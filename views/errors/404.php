<?php
/**
 * Vista de error 404 para la aplicación El Faro
 * 
 * Esta vista muestra la página de error 404 cuando no se encuentra
 * la ruta solicitada.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */
?>

<!-- Sección de error 404 -->
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-danger shadow-lg">
            <div class="card-header bg-danger text-white text-center">
                <h1 class="display-1 fw-bold mb-0">
                    <i class="bi bi-exclamation-triangle me-3"></i>
                    <?= $this->escape($error_code ?? '404') ?>
                </h1>
            </div>
            <div class="card-body text-center p-5">
                <h2 class="text-danger fw-bold mb-3">
                    <?= $this->escape($error_message ?? 'Página no encontrada') ?>
                </h2>
                <p class="lead text-muted mb-4">
                    <?= $this->escape($error_description ?? 'La página que buscas no existe o ha sido movida.') ?>
                </p>
                
                <!-- Ilustración de error -->
                <div class="mb-4">
                    <i class="bi bi-search text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                </div>
                
                <!-- Sugerencias -->
                <?php if (isset($suggestions) && !empty($suggestions)): ?>
                    <div class="alert alert-info text-start">
                        <h5 class="alert-heading">
                            <i class="bi bi-lightbulb me-2"></i>¿Qué puedes hacer?
                        </h5>
                        <ul class="mb-0">
                            <?php foreach ($suggestions as $suggestion): ?>
                                <li><?= $this->escape($suggestion) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Botones de acción -->
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?= $this->url('/') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-house me-2"></i>Volver al Inicio
                    </a>
                    <a href="<?= $this->url('/articulos') ?>" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-newspaper me-2"></i>Ver Artículos
                    </a>
                    <button onclick="history.back()" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Página Anterior
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h4 class="text-muted mb-3">
                    <i class="bi bi-info-circle me-2"></i>Información Adicional
                </h4>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <span class="text-muted">Hora actual: <?= date('d/m/Y H:i:s') ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-globe text-primary me-2"></i>
                            <span class="text-muted">URL solicitada: <?= $this->escape($_SERVER['REQUEST_URI'] ?? '/') ?></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            <span class="text-muted">Sistema: <?= $this->escape($app_name) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enlaces útiles -->
<div class="row mt-4">
    <div class="col-12">
        <h5 class="text-primary fw-bold mb-3">
            <i class="bi bi-link-45deg me-2"></i>Enlaces Útiles
        </h5>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-house text-primary" style="font-size: 2rem;"></i>
                <h6 class="card-title mt-2">Inicio</h6>
                <a href="<?= $this->url('/') ?>" class="btn btn-sm btn-outline-primary">
                    Ir al Inicio
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-newspaper text-info" style="font-size: 2rem;"></i>
                <h6 class="card-title mt-2">Artículos</h6>
                <a href="<?= $this->url('/articulos') ?>" class="btn btn-sm btn-outline-info">
                    Ver Artículos
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-envelope text-success" style="font-size: 2rem;"></i>
                <h6 class="card-title mt-2">Contacto</h6>
                <a href="<?= $this->url('/contacto') ?>" class="btn btn-sm btn-outline-success">
                    Contactar
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-question-circle text-warning" style="font-size: 2rem;"></i>
                <h6 class="card-title mt-2">Ayuda</h6>
                <a href="#" class="btn btn-sm btn-outline-warning">
                    Obtener Ayuda
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para funcionalidad adicional -->
<script>
    // Función para ir a la página anterior
    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '<?= $this->url('/') ?>';
        }
    }
    
    // Función para buscar en el sitio
    function searchSite() {
        const searchTerm = prompt('¿Qué estás buscando?');
        if (searchTerm) {
            // En una implementación real, aquí se haría una búsqueda
            alert('Búsqueda: ' + searchTerm + '\n\nEsta funcionalidad estará disponible próximamente.');
        }
    }
    
    // Agregar evento al botón de búsqueda si existe
    document.addEventListener('DOMContentLoaded', function() {
        const searchButton = document.querySelector('[data-bs-toggle="search"]');
        if (searchButton) {
            searchButton.addEventListener('click', searchSite);
        }
    });
</script>
