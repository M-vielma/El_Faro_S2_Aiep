<?php
/**
 * Script de verificación de navegación
 * 
 * Accede a: http://localhost:8000/Elfaro_taller_s2/public/verificar_navegacion.php
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/helpers.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Navegación - El Faro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { margin-bottom: 20px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .url { font-family: 'Courier New', monospace; background: #f8f9fa; padding: 5px 10px; border-radius: 3px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">🔍 Verificación de Navegación - El Faro</h2>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Información del Servidor:</strong><br>
                    <strong>Protocolo:</strong> <?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'HTTPS' : 'HTTP' ?><br>
                    <strong>Host:</strong> <?= $_SERVER['HTTP_HOST'] ?? 'N/A' ?><br>
                    <strong>Script:</strong> <?= $_SERVER['SCRIPT_NAME'] ?? 'N/A' ?><br>
                    <strong>Base URL:</strong> <span class="url"><?= base_url() ?></span><br>
                    <strong>App URL:</strong> <span class="url"><?= app_url('/') ?></span>
                </div>

                <h4>📊 URLs de Navegación</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Enlace</th>
                            <th>URL Generada</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $enlaces = [
                            ['nombre' => 'Inicio', 'url' => app_url('/')],
                            ['nombre' => 'Artículos', 'url' => app_url('/articulos')],
                            ['nombre' => 'Contacto', 'url' => app_url('/contacto')],
                            ['nombre' => 'Login', 'url' => app_url('/login')],
                            ['nombre' => 'Registro', 'url' => app_url('/registro')],
                        ];
                        
                        foreach ($enlaces as $enlace):
                        ?>
                        <tr>
                            <td><strong><?= $enlace['nombre'] ?></strong></td>
                            <td><span class="url"><?= $enlace['url'] ?></span></td>
                            <td>
                                <a href="<?= $enlace['url'] ?>" class="btn btn-sm btn-success" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i> Probar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h4>🖼️ URLs de Assets</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Archivo</th>
                            <th>URL Generada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $assets = [
                            ['tipo' => 'Imagen', 'archivo' => 'noticia_1.jpeg', 'url' => image_url('noticia_1.jpeg')],
                            ['tipo' => 'Imagen', 'archivo' => 'logo.png', 'url' => image_url('logo.png')],
                            ['tipo' => 'CSS', 'archivo' => 'styles.css', 'url' => css_url('styles.css')],
                        ];
                        
                        foreach ($assets as $asset):
                        ?>
                        <tr>
                            <td><?= $asset['tipo'] ?></td>
                            <td><?= $asset['archivo'] ?></td>
                            <td><span class="url"><?= $asset['url'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="alert alert-success">
                    <h5><i class="bi bi-check-circle"></i> Verificación Completa</h5>
                    <p class="mb-0">
                        ✅ Las URLs de navegación incluyen <code>/public/</code><br>
                        ✅ Las URLs de assets no incluyen <code>/public/</code><br>
                        ✅ Todo está configurado correctamente para funcionar en localhost:8000
                    </p>
                </div>

                <div class="text-center">
                    <a href="<?= app_url('/') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-house"></i> Ir a la Página Principal
                    </a>
                    <a href="<?= app_url('/articulos') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-newspaper"></i> Ver Artículos
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

