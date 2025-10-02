<?php
/**
 * Vista de redirección para la aplicación El Faro
 * 
 * Esta vista maneja las redirecciones cuando el .htaccess no funciona
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirigiendo - El Faro</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            margin: 0;
        }
        
        .redirect-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            padding: 2rem;
            text-align: center;
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        
        .spinner {
            width: 3rem;
            height: 3rem;
            border: 0.3rem solid #f3f3f3;
            border-top: 0.3rem solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Responsividad mejorada */
        @media (max-width: 576px) {
            .redirect-card {
                padding: 1.5rem;
                margin: 0.5rem;
                border-radius: 10px;
            }
            
            .spinner {
                width: 2.5rem;
                height: 2.5rem;
            }
            
            h4 {
                font-size: 1.1rem;
            }
            
            p {
                font-size: 0.9rem;
            }
            
            .btn {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }
            
            .redirect-card {
                padding: 1rem;
            }
            
            .d-grid {
                gap: 0.5rem !important;
            }
        }
    </style>
</head>
<body>
    <div class="redirect-card">
        <div class="spinner"></div>
        <h4 class="text-primary mb-3">
            <i class="bi bi-arrow-clockwise me-2"></i>
            Redirigiendo...
        </h4>
        <p class="text-muted mb-4"><?= $this->escape($message ?? 'Redirigiendo...') ?></p>
        
        <div class="d-grid gap-2">
            <a href="<?= $this->escape($url ?? '/') ?>" class="btn btn-primary">
                <i class="bi bi-arrow-right me-2"></i>Continuar
            </a>
            <a href="<?= base_url() ?>" class="btn btn-outline-secondary">
                <i class="bi bi-house me-2"></i>Ir al Inicio
            </a>
        </div>
    </div>

    <!-- JavaScript para redirección automática -->
    <script>
        // Redirigir automáticamente después de 3 segundos
        setTimeout(function() {
            window.location.href = '<?= $this->escape($url ?? '/') ?>';
        }, 3000);
        
        // Mostrar contador regresivo
        let countdown = 3;
        const messageElement = document.querySelector('p');
        const originalMessage = messageElement.textContent;
        
        const timer = setInterval(function() {
            countdown--;
            messageElement.textContent = originalMessage + ' (' + countdown + ' segundos)';
            
            if (countdown <= 0) {
                clearInterval(timer);
                messageElement.textContent = 'Redirigiendo ahora...';
            }
        }, 1000);
    </script>
</body>
</html>
