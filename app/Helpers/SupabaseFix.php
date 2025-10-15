<?php
/**
 * Fix para compatibilidad de Supabase en producción
 * 
 * Este archivo define las constantes necesarias para que Supabase
 * funcione correctamente en entornos de producción donde STDERR
 * no está definida por defecto.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Definir STDERR si no existe (compatibilidad con producción)
if (!defined('STDERR')) {
    define('STDERR', fopen('php://stderr', 'w'));
}

// Definir STDIN si no existe
if (!defined('STDIN')) {
    define('STDIN', fopen('php://stdin', 'r'));
}

// Definir STDOUT si no existe
if (!defined('STDOUT')) {
    define('STDOUT', fopen('php://stdout', 'w'));
}

