<?php
/**
 * Vista de perfil para la aplicación El Faro
 * 
 * Esta vista muestra el perfil del usuario autenticado
 * con opciones de suscripción y gestión de cuenta.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Comentamos: Obtenemos los datos del usuario autenticado
$usuario = $data['usuario'] ?? null;
$estadisticas = PerfilController::getEstadisticasUsuario();
?>

<!-- Breadcrumb de navegación -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= $this->url('/') ?>" class="text-decoration-none">
                <i class="bi bi-house me-1"></i>Inicio
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
    </ol>
</nav>

<!-- Mensajes flash -->
<?php if (flash_get('error')): ?>
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= $this->escape(flash_get('error')) ?>
    </div>
<?php endif; ?>

<?php if (flash_get('success')): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        <?= $this->escape(flash_get('success')) ?>
    </div>
<?php endif; ?>

<?php if ($usuario): ?>
<div class="row">
    <!-- Información del usuario -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0">
                    <i class="bi bi-person-circle me-2"></i>Información Personal
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= $this->url('/perfil/actualizar') ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre Completo</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= $this->escape($usuario['nombre']) ?>"
                                   required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= $this->escape($usuario['email']) ?>"
                                   required>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="newsletter" 
                                       name="newsletter" 
                                       <?= $usuario['suscrito'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="newsletter">
                                    <i class="bi bi-bell me-1"></i>Recibir newsletter de El Faro
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Actualizar Perfil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Panel lateral -->
    <div class="col-lg-4">
        <!-- Estado de suscripción -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-<?= $usuario['plan'] === 'premium' ? 'success' : 'secondary' ?> text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-<?= $usuario['plan'] === 'premium' ? 'star-fill' : 'person' ?> me-2"></i>
                    Plan <?= ucfirst($usuario['plan']) ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if ($usuario['plan'] === 'premium'): ?>
                    <div class="alert alert-success">
                        <h6 class="alert-heading">
                            <i class="bi bi-check-circle me-2"></i>¡Suscripción Premium Activa!
                        </h6>
                        <p class="mb-0">Disfruta de todos los beneficios premium de El Faro.</p>
                    </div>
                    
                    <form method="POST" action="<?= $this->url('/perfil/desactivar-suscripcion') ?>" class="d-grid">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-x-circle me-1"></i>Cancelar Suscripción
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Plan Gratuito
                        </h6>
                        <p class="mb-0">Actualiza a Premium para acceder a contenido exclusivo.</p>
                    </div>
                    
                    <form method="POST" action="<?= $this->url('/perfil/activar-suscripcion') ?>" class="d-grid">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-star me-1"></i>Activar Premium
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estadísticas del usuario -->
        <?php if (!empty($estadisticas)): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart me-2"></i>Estadísticas
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-calendar text-primary me-2"></i>
                        <strong>Miembro desde:</strong> <?= $estadisticas['dias_registrado'] ?> días
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-<?= $usuario['plan'] === 'premium' ? 'star-fill' : 'person' ?> text-primary me-2"></i>
                        <strong>Plan actual:</strong> <?= ucfirst($usuario['plan']) ?>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-<?= $usuario['suscrito'] ? 'bell-fill' : 'bell-slash' ?> text-primary me-2"></i>
                        <strong>Newsletter:</strong> <?= $usuario['suscrito'] ? 'Activo' : 'Inactivo' ?>
                    </li>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <!-- Beneficios Premium -->
        <?php if ($usuario['plan'] === 'premium' && !empty($estadisticas['beneficios_premium'])): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gift me-2"></i>Beneficios Premium
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php foreach ($estadisticas['beneficios_premium'] as $beneficio): ?>
                        <li class="mb-1">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <?= $this->escape($beneficio) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Acciones adicionales -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h5 class="text-primary mb-3">
                    <i class="bi bi-gear me-2"></i>Acciones de Cuenta
                </h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="<?= $this->url('/') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-house me-2"></i>Ir al Inicio
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $this->url('/articulos') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-newspaper me-2"></i>Ver Artículos
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $this->url('/contacto') ?>" class="btn btn-outline-primary">
                            <i class="bi bi-envelope me-2"></i>Contacto
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?= $this->url('/logout') ?>" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Usuario no autenticado -->
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-person-x text-danger" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Acceso Denegado</h4>
                <p class="text-muted">Debes iniciar sesión para acceder a tu perfil.</p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="<?= $this->url('/login') ?>" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                    </a>
                    <a href="<?= $this->url('/registro') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-person-plus me-2"></i>Registrarse
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- JavaScript para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    if (form) {
        // Validación en tiempo real
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
        
        // Validación de email en tiempo real
        const email = document.getElementById('email');
        if (email) {
            email.addEventListener('input', function() {
                if (email.validity.typeMismatch) {
                    email.setCustomValidity('Por favor ingresa un correo electrónico válido');
                    email.classList.add('is-invalid');
                } else {
                    email.setCustomValidity('');
                    email.classList.remove('is-invalid');
                }
            });
        }
    }
});
</script>
