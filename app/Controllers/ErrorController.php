<?php
/**
 * Controlador de errores para la aplicación El Faro
 * 
 * Este controlador maneja los errores de la aplicación,
 * incluyendo páginas 404 y otros errores del servidor.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class ErrorController extends Controller {
    
    /**
     * Página 404 - No encontrado
     * 
     * Muestra la página de error 404 cuando no se encuentra una ruta
     */
    public function notFound() {
        // Establecer código de estado HTTP 404
        http_response_code(404);
        
        // Establecer título de la página
        $this->setTitle('Página no encontrada');
        
        // Establecer descripción meta
        $this->setMetaDescription('La página que buscas no existe en El Faro');
        
        // Datos adicionales para la vista
        $this->setData('error_code', '404');
        $this->setData('error_message', 'Página no encontrada');
        $this->setData('error_description', 'La página que buscas no existe o ha sido movida.');
        $this->setData('suggestions', [
            'Verificar que la URL sea correcta',
            'Usar el menú de navegación para encontrar lo que buscas',
            'Volver a la página de inicio'
        ]);
        
        // Renderizar la vista de error
        $this->view('errors/404');
    }
    
    /**
     * Error 500 - Error interno del servidor
     * 
     * Muestra la página de error 500 para errores del servidor
     */
    public function serverError() {
        // Establecer código de estado HTTP 500
        http_response_code(500);
        
        // Establecer título de la página
        $this->setTitle('Error del servidor');
        
        // Establecer descripción meta
        $this->setMetaDescription('Ha ocurrido un error interno del servidor');
        
        // Datos adicionales para la vista
        $this->setData('error_code', '500');
        $this->setData('error_message', 'Error interno del servidor');
        $this->setData('error_description', 'Ha ocurrido un error inesperado. Nuestro equipo técnico ha sido notificado.');
        $this->setData('suggestions', [
            'Intentar recargar la página',
            'Esperar unos minutos y volver a intentar',
            'Contactar al soporte técnico si el problema persiste'
        ]);
        
        // Renderizar la vista de error
        $this->view('errors/500');
    }
    
    /**
     * Error 403 - Acceso denegado
     * 
     * Muestra la página de error 403 para acceso denegado
     */
    public function forbidden() {
        // Establecer código de estado HTTP 403
        http_response_code(403);
        
        // Establecer título de la página
        $this->setTitle('Acceso denegado');
        
        // Establecer descripción meta
        $this->setMetaDescription('No tienes permisos para acceder a esta página');
        
        // Datos adicionales para la vista
        $this->setData('error_code', '403');
        $this->setData('error_message', 'Acceso denegado');
        $this->setData('error_description', 'No tienes permisos para acceder a esta página.');
        $this->setData('suggestions', [
            'Verificar que tengas los permisos necesarios',
            'Iniciar sesión si no lo has hecho',
            'Contactar al administrador si crees que es un error'
        ]);
        
        // Renderizar la vista de error
        $this->view('errors/403');
    }
    
    /**
     * Error 401 - No autorizado
     * 
     * Muestra la página de error 401 para usuarios no autenticados
     */
    public function unauthorized() {
        // Establecer código de estado HTTP 401
        http_response_code(401);
        
        // Establecer título de la página
        $this->setTitle('No autorizado');
        
        // Establecer descripción meta
        $this->setMetaDescription('Debes iniciar sesión para acceder a esta página');
        
        // Datos adicionales para la vista
        $this->setData('error_code', '401');
        $this->setData('error_message', 'No autorizado');
        $this->setData('error_description', 'Debes iniciar sesión para acceder a esta página.');
        $this->setData('suggestions', [
            'Iniciar sesión con tu cuenta',
            'Crear una cuenta si no tienes una',
            'Verificar que estés en la página correcta'
        ]);
        
        // Renderizar la vista de error
        $this->view('errors/401');
    }
}
