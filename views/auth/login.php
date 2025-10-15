<?php
/**
 * Vista de login para la aplicación El Faro
 * 
 * Esta vista muestra el formulario de inicio de sesión
 * con validación y diseño responsivo.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */
?>

<!-- Breadcrumb de navegación -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= $this->url('/') ?>" class="text-decoration-none">
                <i class="bi bi-house me-1"></i>Inicio
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Iniciar Sesión</li>
    </ol>
</nav>

<!-- Formulario de login -->
<div class="row justify-content-center">
    <div class="col-lg-4 col-md-6">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title mb-0">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </h3>
                <p class="mb-0 mt-2">Accede a tu cuenta de El Faro</p>
            </div>
            <div class="card-body p-4">
                <!-- Mensajes flash -->
                <?php if (flash_has('error')): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= $this->escape(flash_get('error')) ?>
                    </div>
                <?php endif; ?>

                <?php if (flash_has('success')): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= $this->escape(flash_get('success')) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $this->url('/login') ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Correo Electrónico
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?= $this->escape($email ?? $_POST['email'] ?? '') ?>"
                               required
                               autocomplete="email">
                        <div class="invalid-feedback">
                            Por favor ingresa un correo electrónico válido.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>Contraseña
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               required
                               autocomplete="current-password">
                        <div class="invalid-feedback">
                            Por favor ingresa tu contraseña.
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="remember" 
                               name="remember" 
                               value="1">
                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="#" class="text-decoration-none">
                            <i class="bi bi-question-circle me-1"></i>¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center bg-light">
                <p class="mb-0">
                    ¿No tienes cuenta? 
                    <a href="<?= $this->url('/registro') ?>" class="text-decoration-none fw-bold">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row justify-content-center mt-4">
    <div class="col-lg-8">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h5 class="text-primary mb-3">
                    <i class="bi bi-shield-check me-2"></i>¿Por qué crear una cuenta?
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <i class="bi bi-bell text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">Newsletter Personalizado</h6>
                        <p class="text-muted small">Recibe noticias de tu interés</p>
                    </div>
                    <div class="col-md-4">
                        <i class="bi bi-bookmark text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">Guardar Artículos</h6>
                        <p class="text-muted small">Marca tus noticias favoritas</p>
                    </div>
                    <div class="col-md-4">
                        <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">Perfil Personal</h6>
                        <p class="text-muted small">Personaliza tu experiencia</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
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
    email.addEventListener('input', function() {
        if (email.validity.typeMismatch) {
            email.setCustomValidity('Por favor ingresa un correo electrónico válido');
            email.classList.add('is-invalid');
        } else {
            email.setCustomValidity('');
            email.classList.remove('is-invalid');
        }
    });
    
    // Validación de contraseña en tiempo real
    const password = document.getElementById('password');
    password.addEventListener('input', function() {
        if (password.value.length < 6) {
            password.setCustomValidity('La contraseña debe tener al menos 6 caracteres');
            password.classList.add('is-invalid');
        } else {
            password.setCustomValidity('');
            password.classList.remove('is-invalid');
        }
    });
});
</script>
