<?php
/**
 * Vista de registro para la aplicación El Faro
 * 
 * Esta vista muestra el formulario de registro de usuarios
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
        <li class="breadcrumb-item active" aria-current="page">Registro</li>
    </ol>
</nav>

<!-- Formulario de registro -->
<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title mb-0">
                    <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                </h3>
                <p class="mb-0 mt-2">Únete a El Faro y mantente informado</p>
            </div>
            <div class="card-body p-4">
                <!-- Mensajes de error -->
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">Por favor corrige los siguientes errores:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= $this->escape($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Mensajes de éxito -->
                <?php if (isset($success) && $success): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        <strong>¡Registro exitoso!</strong> Tu cuenta ha sido creada correctamente.
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $this->url('/registro') ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <!-- Nombre completo -->
                        <div class="col-12">
                            <label for="nombre" class="form-label">
                                <i class="bi bi-person me-1"></i>Nombre Completo
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?= $this->escape($_POST['nombre'] ?? '') ?>"
                                   required>
                            <div class="invalid-feedback">
                                Por favor ingresa tu nombre completo.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-12">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>Correo Electrónico
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?= $this->escape($_POST['email'] ?? '') ?>"
                                   required>
                            <div class="invalid-feedback">
                                Por favor ingresa un correo electrónico válido.
                            </div>
                        </div>

                        <!-- Contraseña -->
                        <div class="col-12">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-1"></i>Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required
                                   minlength="6">
                            <div class="invalid-feedback">
                                La contraseña debe tener al menos 6 caracteres.
                            </div>
                        </div>

                        <!-- Confirmar contraseña -->
                        <div class="col-12">
                            <label for="password_confirm" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i>Confirmar Contraseña
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   required>
                            <div class="invalid-feedback">
                                Las contraseñas no coinciden.
                            </div>
                        </div>

                        <!-- Plan de suscripción -->
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-star me-1"></i>Plan de Suscripción
                            </label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="plan" 
                                               id="plan_free" 
                                               value="free" 
                                               checked>
                                        <label class="form-check-label" for="plan_free">
                                            <strong>Gratuito</strong><br>
                                            <small class="text-muted">Acceso básico a noticias</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="plan" 
                                               id="plan_premium" 
                                               value="premium">
                                        <label class="form-check-label" for="plan_premium">
                                            <strong>Premium</strong><br>
                                            <small class="text-muted">Acceso completo + newsletter</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Suscripción al newsletter -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="suscrito" 
                                       name="suscrito" 
                                       value="1" 
                                       checked>
                                <label class="form-check-label" for="suscrito">
                                    <i class="bi bi-bell me-1"></i>Suscribirme al newsletter de El Faro
                                </label>
                            </div>
                        </div>

                        <!-- Términos y condiciones -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="terminos" 
                                       name="terminos" 
                                       value="1" 
                                       required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> 
                                    y la <a href="#" class="text-decoration-none">política de privacidad</a>
                                </label>
                                <div class="invalid-feedback">
                                    Debes aceptar los términos y condiciones.
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                                </button>
                                <a href="<?= $this->url('/login') ?>" class="btn btn-outline-secondary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>¿Ya tienes cuenta? Inicia Sesión
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para validación del formulario -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    
    // Validación en tiempo real
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Validar que las contraseñas coincidan
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
            passwordConfirm.classList.add('is-invalid');
        } else {
            passwordConfirm.setCustomValidity('');
            passwordConfirm.classList.remove('is-invalid');
        }
        
        form.classList.add('was-validated');
    });
    
    // Validación de contraseñas en tiempo real
    passwordConfirm.addEventListener('input', function() {
        if (password.value !== passwordConfirm.value) {
            passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
            passwordConfirm.classList.add('is-invalid');
        } else {
            passwordConfirm.setCustomValidity('');
            passwordConfirm.classList.remove('is-invalid');
        }
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
});
</script>
