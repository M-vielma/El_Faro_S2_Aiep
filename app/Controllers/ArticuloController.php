<?php
/**
 * Controlador de Artículos para la aplicación El Faro
 * 
 * Este controlador maneja las operaciones relacionadas con artículos,
 * incluyendo el listado y la visualización de detalles.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

// Cargar modelos y repositorios
require_once __DIR__ . '/../Models/Articulo.php';
require_once __DIR__ . '/../Models/Repositorios/ArticuloRepoMem.php';

class ArticuloController extends Controller {
    
    /**
     * Instancia del repositorio de artículos
     * 
     * @var ArticuloRepoMem
     */
    private $articuloRepo;
    
    /**
     * Constructor del controlador
     * 
     * Inicializa el repositorio de artículos para las operaciones
     */
    public function __construct() {
        parent::__construct();
        $this->articuloRepo = new ArticuloRepoMem();
    }
    
    /**
     * Página de listado de artículos
     * 
     * Muestra una grilla de artículos con paginación simple
     * utilizando Bootstrap 5 para el diseño responsivo
     */
    public function index() {
        // Establecer título y metadatos de la página
        $this->setTitle('Artículos');
        $this->setMetaDescription('Explora todos los artículos y noticias de El Faro');
        $this->setMetaKeywords('artículos, noticias, El Faro, actualidad, información');
        
        // Obtener parámetros de paginación
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 6; // Artículos por página
        
        // Obtener artículos paginados del repositorio
        $resultadoPaginacion = $this->articuloRepo->paginate($pagina, $porPagina);
        $articulos = $resultadoPaginacion['articulos'];
        $paginacion = $resultadoPaginacion['paginacion'];
        
        // Obtener categorías para filtros
        $categorias = $this->articuloRepo->getCategorias();
        
        // Obtener estadísticas de artículos
        $estadisticas = $this->articuloRepo->getEstadisticas();
        
        // Pasar datos a la vista
        $this->setData('articulos', $articulos);
        $this->setData('paginacion', $paginacion);
        $this->setData('categorias', $categorias);
        $this->setData('estadisticas', $estadisticas);
        $this->setData('pagina_actual', $pagina);
        
        // Renderizar la vista de listado
        $this->view('articulos/index');
    }
    
    /**
     * Página de detalle de un artículo
     * 
     * Muestra el contenido completo de un artículo específico
     * con información detallada y diseño responsivo
     * 
     * @param int $id ID del artículo a mostrar
     */
    public function show($id) {
        // Mostrar mensaje de "Artículo en desarrollo" para todos los artículos
        $this->setData('mensaje', 'Artículo en desarrollo');
        $this->setData('titulo_pagina', 'Artículo en desarrollo');
        $this->setData('meta_description', 'Este artículo está en desarrollo');
        
        // Mostrar la vista con el mensaje
        $this->view('articulos/show');
    }
    
    /**
     * Buscar artículos por texto
     * 
     * Realiza una búsqueda de artículos por título, contenido o bajada
     * y muestra los resultados con paginación
     */
    public function search() {
        // Obtener término de búsqueda
        $termino = $_GET['q'] ?? '';
        $termino = trim($termino);
        
        // Establecer título de la página
        $this->setTitle('Búsqueda: ' . $termino);
        $this->setMetaDescription('Resultados de búsqueda para: ' . $termino);
        
        $articulos = [];
        $paginacion = null;
        
        // Si hay término de búsqueda, realizar la búsqueda
        if (!empty($termino)) {
            $articulos = $this->articuloRepo->search($termino);
            
            // Paginación simple para resultados de búsqueda
            $pagina = (int)($_GET['pagina'] ?? 1);
            $porPagina = 6;
            $total = count($articulos);
            $totalPaginas = ceil($total / $porPagina);
            $offset = ($pagina - 1) * $porPagina;
            
            $articulos = array_slice($articulos, $offset, $porPagina);
            
            $paginacion = [
                'pagina_actual' => $pagina,
                'total_paginas' => $totalPaginas,
                'total_articulos' => $total,
                'por_pagina' => $porPagina,
                'tiene_anterior' => $pagina > 1,
                'tiene_siguiente' => $pagina < $totalPaginas
            ];
        }
        
        // Pasar datos a la vista
        $this->setData('articulos', $articulos);
        $this->setData('paginacion', $paginacion);
        $this->setData('termino_busqueda', $termino);
        $this->setData('total_resultados', count($articulos));
        
        // Renderizar vista de búsqueda (reutilizar index con datos diferentes)
        $this->view('articulos/search');
    }
    
    /**
     * Filtrar artículos por categoría
     * 
     * Muestra artículos filtrados por una categoría específica
     * 
     * @param string $categoria Categoría a filtrar
     */
    public function categoria($categoria) {
        // Establecer título y metadatos
        $this->setTitle('Categoría: ' . ucfirst($categoria));
        $this->setMetaDescription('Artículos de la categoría ' . $categoria);
        
        // Obtener artículos de la categoría
        $articulos = $this->articuloRepo->findByCategoria($categoria);
        
        // Paginación simple
        $pagina = (int)($_GET['pagina'] ?? 1);
        $porPagina = 6;
        $total = count($articulos);
        $totalPaginas = ceil($total / $porPagina);
        $offset = ($pagina - 1) * $porPagina;
        
        $articulos = array_slice($articulos, $offset, $porPagina);
        
        $paginacion = [
            'pagina_actual' => $pagina,
            'total_paginas' => $totalPaginas,
            'total_articulos' => $total,
            'por_pagina' => $porPagina,
            'tiene_anterior' => $pagina > 1,
            'tiene_siguiente' => $pagina < $totalPaginas
        ];
        
        // Pasar datos a la vista
        $this->setData('articulos', $articulos);
        $this->setData('paginacion', $paginacion);
        $this->setData('categoria_actual', $categoria);
        $this->setData('total_resultados', $total);
        
        // Renderizar vista de categoría
        $this->view('articulos/categoria');
    }
    
    /**
     * Obtener artículos recientes para AJAX
     * 
     * Endpoint para obtener artículos recientes via AJAX
     * útil para cargar contenido dinámico
     */
    public function recientes() {
        // Verificar que sea una petición AJAX
        if (!$this->isAjax()) {
            $this->redirect(base_url() . '/articulos');
            return;
        }
        
        // Obtener parámetros
        $limite = (int)($_GET['limite'] ?? 5);
        $categoria = $_GET['categoria'] ?? null;
        
        // Obtener artículos
        if ($categoria) {
            $articulos = $this->articuloRepo->findByCategoria($categoria);
        } else {
            $articulos = $this->articuloRepo->allOrderedByDate();
        }
        
        // Limitar resultados
        $articulos = array_slice($articulos, 0, $limite);
        
        // Convertir a array para JSON
        $articulosArray = array_map(function($articulo) {
            return $articulo->getInfoPublica();
        }, $articulos);
        
        // Responder con JSON
        $this->json([
            'success' => true,
            'articulos' => $articulosArray,
            'total' => count($articulosArray)
        ]);
    }
}
