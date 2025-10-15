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

                        <!-- Selección de plan -->
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-credit-card me-1"></i>Selecciona tu Plan de Suscripción
                            </label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="card h-100 border-2 plan-card" data-plan="5000">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">Plan Básico</h5>
                                            <div class="fs-3 fw-bold text-success">$5.000</div>
                                            <div class="text-muted small">CLP/mes</div>
                                            <ul class="list-unstyled mt-3">
                                                <li><i class="bi bi-check text-success me-1"></i>Acceso a noticias</li>
                                                <li><i class="bi bi-check text-success me-1"></i>Newsletter diario</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 border-2 plan-card border-primary" data-plan="10000">
                                        <div class="card-body text-center">
                                            <div class="badge bg-primary mb-2">Recomendado</div>
                                            <h5 class="card-title text-primary">Plan Premium</h5>
                                            <div class="fs-3 fw-bold text-success">$10.000</div>
                                            <div class="text-muted small">CLP/mes</div>
                                            <ul class="list-unstyled mt-3">
                                                <li><i class="bi bi-check text-success me-1"></i>Todo del Plan Básico</li>
                                                <li><i class="bi bi-check text-success me-1"></i>Análisis exclusivos</li>
                                                <li><i class="bi bi-check text-success me-1"></i>Sin publicidad</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 border-2 plan-card" data-plan="15000">
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-primary">Plan VIP</h5>
                                            <div class="fs-3 fw-bold text-success">$15.000</div>
                                            <div class="text-muted small">CLP/mes</div>
                                            <ul class="list-unstyled mt-3">
                                                <li><i class="bi bi-check text-success me-1"></i>Todo del Plan Premium</li>
                                                <li><i class="bi bi-check text-success me-1"></i>Contenido exclusivo</li>
                                                <li><i class="bi bi-check text-success me-1"></i>Soporte prioritario</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="plan_seleccionado" name="plan" value="10000">
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
    const planSeleccionado = document.getElementById('plan_seleccionado');
    const planCards = document.querySelectorAll('.plan-card');
    
    // Manejo de selección de planes
    planCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remover selección anterior
            planCards.forEach(c => {
                c.classList.remove('border-primary', 'bg-primary', 'text-white');
                c.classList.add('border-secondary');
            });
            
            // Seleccionar plan actual
            this.classList.remove('border-secondary');
            this.classList.add('border-primary', 'bg-primary', 'text-white');
            
            // Actualizar valor del plan
            const plan = this.getAttribute('data-plan');
            planSeleccionado.value = plan;
        });
    });
    
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
