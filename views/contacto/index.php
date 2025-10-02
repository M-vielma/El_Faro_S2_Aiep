<?php
/**
 * Vista de contacto para la aplicación El Faro
 * 
 * Esta vista muestra el formulario de contacto
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
        <li class="breadcrumb-item active" aria-current="page">Contacto</li>
    </ol>
</nav>

<!-- Formulario de contacto -->
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="card-title mb-0">
                    <i class="bi bi-envelope me-2"></i>Contáctanos
                </h3>
                <p class="mb-0 mt-2">Envíanos tu mensaje y te responderemos pronto</p>
            </div>
            <div class="card-body p-4">
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

                <!-- Información del último mensaje enviado -->
                <?php 
                $ultimoMensaje = ContactoController::getUltimoMensaje();
                if ($ultimoMensaje): 
                ?>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Último mensaje enviado
                        </h6>
                        <p class="mb-0">
                            <strong>De:</strong> <?= $this->escape($ultimoMensaje['nombre']) ?> 
                            (<?= $this->escape($ultimoMensaje['email']) ?>)<br>
                            <strong>Fecha:</strong> <?= $this->escape($ultimoMensaje['fecha']) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $this->url('/contacto') ?>" novalidate>
                    <?= csrf_field() ?>
                    
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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

                        <!-- Mensaje -->
                        <div class="col-12">
                            <label for="mensaje" class="form-label">
                                <i class="bi bi-chat-text me-1"></i>Mensaje
                            </label>
                            <textarea class="form-control" 
                                      id="mensaje" 
                                      name="mensaje" 
                                      rows="6" 
                                      required
                                      minlength="10"
                                      maxlength="1000"
                                      placeholder="Escribe tu mensaje aquí..."><?= $this->escape($_POST['mensaje'] ?? '') ?></textarea>
                            <div class="invalid-feedback">
                                El mensaje debe tener entre 10 y 1000 caracteres.
                            </div>
                            <div class="form-text">
                                Mínimo 10 caracteres, máximo 1000 caracteres.
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="col-12">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i>Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Información adicional -->
<div class="row justify-content-center mt-4">
    <div class="col-lg-8 col-md-10">
        <div class="card border-0 bg-light">
            <div class="card-body text-center">
                <h5 class="text-primary mb-3">
                    <i class="bi bi-info-circle me-2"></i>Información de Contacto
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <i class="bi bi-envelope text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">Email</h6>
                        <p class="text-muted small">contacto@elfaro.com</p>
                    </div>
                    <div class="col-md-4">
                        <i class="bi bi-telephone text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">Teléfono</h6>
                        <p class="text-muted small">+1 (555) 123-4567</p>
                    </div>
                    <div class="col-md-4">
                        <i class="bi bi-clock text-primary" style="font-size: 2rem;"></i>
                        <h6 class="mt-2">Horario</h6>
                        <p class="text-muted small">Lun - Vie: 9:00 - 18:00</p>
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
    const mensaje = document.getElementById('mensaje');
    
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
    
    // Validación de mensaje en tiempo real
    mensaje.addEventListener('input', function() {
        if (mensaje.value.length < 10) {
            mensaje.setCustomValidity('El mensaje debe tener al menos 10 caracteres');
            mensaje.classList.add('is-invalid');
        } else if (mensaje.value.length > 1000) {
            mensaje.setCustomValidity('El mensaje no puede exceder 1000 caracteres');
            mensaje.classList.add('is-invalid');
        } else {
            mensaje.setCustomValidity('');
            mensaje.classList.remove('is-invalid');
        }
    });
    
    // Contador de caracteres
    const contador = document.createElement('div');
    contador.className = 'form-text text-end';
    contador.innerHTML = '<span id="contador">0</span>/1000 caracteres';
    mensaje.parentNode.appendChild(contador);
    
    mensaje.addEventListener('input', function() {
        const contadorSpan = document.getElementById('contador');
        contadorSpan.textContent = mensaje.value.length;
        
        if (mensaje.value.length > 1000) {
            contadorSpan.style.color = 'red';
        } else if (mensaje.value.length > 800) {
            contadorSpan.style.color = 'orange';
        } else {
            contadorSpan.style.color = 'inherit';
        }
    });
});
</script>
