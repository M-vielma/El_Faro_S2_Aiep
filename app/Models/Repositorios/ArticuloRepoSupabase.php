<?php
/**
 * Repositorio de Artículos con Supabase para la aplicación El Faro
 * 
 * Este repositorio maneja la persistencia de artículos utilizando Supabase
 * como backend de base de datos.
 * 
 * @author Equipo de Desarrollo
 * @version 2.0
 */

require_once __DIR__ . '/../Articulo.php';
require_once __DIR__ . '/../../Services/SupabaseService.php';

use ElFaro\Services\SupabaseService;

class ArticuloRepoSupabase
{
    /**
     * Nombre de la tabla de artículos en Supabase
     * 
     * @var string
     */
    private const TABLE_NAME = 'articulos';
    
    /**
     * Constructor del repositorio
     * 
     * Inicializa la conexión con Supabase.
     */
    public function __construct()
    {
        // Verificar que Supabase esté configurado
        if (!SupabaseService::isConfigured()) {
            throw new \Exception('Supabase no está configurado correctamente');
        }
    }
    
    /**
     * Crea un nuevo artículo en el repositorio
     * 
     * @param Articulo $articulo Artículo a crear
     * @return Articulo Artículo creado con ID asignado
     * @throws Exception Si hay un error
     */
    public function create(Articulo $articulo): Articulo
    {
        // Validar el artículo antes de guardarlo
        $errores = $articulo->validar();
        if (!empty($errores)) {
            throw new Exception("Datos de artículo inválidos: " . implode(', ', $errores));
        }
        
        // Preparar datos para insertar
        $data = [
            'titulo' => $articulo->titulo,
            'bajada' => $articulo->bajada,
            'contenido' => $articulo->contenido,
            'fecha' => $articulo->fecha,
            'autor' => $articulo->autor,
            'categoria' => $articulo->categoria,
            'imagen' => $articulo->imagen
        ];
        
        try {
            // Insertar en Supabase
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)->insert($data)->execute();
            
            // Obtener el artículo creado
            if (isset($response->data[0])) {
                $articuloData = $response->data[0];
                return Articulo::fromArray([
                    'id' => $articuloData['id'],
                    'titulo' => $articuloData['titulo'],
                    'bajada' => $articuloData['bajada'],
                    'contenido' => $articuloData['contenido'],
                    'fecha' => $articuloData['fecha'],
                    'autor' => $articuloData['autor'],
                    'categoria' => $articuloData['categoria'],
                    'imagen' => $articuloData['imagen'] ?? ''
                ]);
            }
            
            throw new Exception('Error al crear el artículo');
        } catch (\Exception $e) {
            throw new Exception('Error al crear artículo: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtiene todos los artículos del repositorio
     * 
     * @return array Array de objetos Articulo
     */
    public function all(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)->select('*')->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return $articulos;
        } catch (\Exception $e) {
            error_log('Error al obtener artículos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca un artículo por su ID
     * 
     * @param int $id ID del artículo a buscar
     * @return Articulo|null Artículo encontrado o null si no existe
     */
    public function find(int $id): ?Articulo
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('id', $id)
                ->execute();
            
            if (isset($response->data[0])) {
                $articuloData = $response->data[0];
                return Articulo::fromArray([
                    'id' => $articuloData['id'],
                    'titulo' => $articuloData['titulo'],
                    'bajada' => $articuloData['bajada'],
                    'contenido' => $articuloData['contenido'],
                    'fecha' => $articuloData['fecha'],
                    'autor' => $articuloData['autor'],
                    'categoria' => $articuloData['categoria'],
                    'imagen' => $articuloData['imagen'] ?? ''
                ]);
            }
            
            return null;
        } catch (\Exception $e) {
            error_log('Error al buscar artículo por ID: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Busca artículos por categoría
     * 
     * @param string $categoria Categoría a buscar
     * @return array Array de artículos de la categoría
     */
    public function findByCategoria(string $categoria): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('categoria', $categoria)
                ->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return $articulos;
        } catch (\Exception $e) {
            error_log('Error al buscar artículos por categoría: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca artículos por autor
     * 
     * @param string $autor Autor a buscar
     * @return array Array de artículos del autor
     */
    public function findByAutor(string $autor): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->eq('autor', $autor)
                ->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return $articulos;
        } catch (\Exception $e) {
            error_log('Error al buscar artículos por autor: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca artículos recientes (últimos N días)
     * 
     * @param int $dias Número de días para considerar reciente
     * @return array Array de artículos recientes
     */
    public function findRecientes(int $dias = 7): array
    {
        try {
            $fechaLimite = date('Y-m-d H:i:s', strtotime("-{$dias} days"));
            
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->gte('fecha', $fechaLimite)
                ->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return $articulos;
        } catch (\Exception $e) {
            error_log('Error al buscar artículos recientes: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Busca artículos por texto en título o contenido
     * 
     * @param string $texto Texto a buscar
     * @return array Array de artículos que coinciden
     */
    public function search(string $texto): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->or('titulo.ilike.%' . $texto . '%,contenido.ilike.%' . $texto . '%,bajada.ilike.%' . $texto . '%')
                ->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return $articulos;
        } catch (\Exception $e) {
            error_log('Error al buscar artículos: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene artículos ordenados por fecha
     * 
     * @param string $orden Orden de clasificación (asc o desc)
     * @return array Array de artículos ordenados
     */
    public function allOrderedByDate(string $orden = 'desc'): array
    {
        try {
            $db = SupabaseService::db();
            $query = $db->from(self::TABLE_NAME)->select('*');
            
            if ($orden === 'desc') {
                $query = $query->order('fecha', ['ascending' => false]);
            } else {
                $query = $query->order('fecha', ['ascending' => true]);
            }
            
            $response = $query->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return $articulos;
        } catch (\Exception $e) {
            error_log('Error al obtener artículos ordenados: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene las categorías disponibles
     * 
     * @return array Array de categorías únicas
     */
    public function getCategorias(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('categoria')
                ->execute();
            
            $categorias = [];
            if (isset($response->data)) {
                foreach ($response->data as $row) {
                    if (!in_array($row['categoria'], $categorias)) {
                        $categorias[] = $row['categoria'];
                    }
                }
            }
            
            return $categorias;
        } catch (\Exception $e) {
            error_log('Error al obtener categorías: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene los autores disponibles
     * 
     * @return array Array de autores únicos
     */
    public function getAutores(): array
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('autor')
                ->execute();
            
            $autores = [];
            if (isset($response->data)) {
                foreach ($response->data as $row) {
                    if (!in_array($row['autor'], $autores)) {
                        $autores[] = $row['autor'];
                    }
                }
            }
            
            return $autores;
        } catch (\Exception $e) {
            error_log('Error al obtener autores: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtiene estadísticas de artículos
     * 
     * @return array Estadísticas de artículos
     */
    public function getEstadisticas(): array
    {
        try {
            $articulos = $this->all();
            $total = count($articulos);
            $categorias = [];
            $autores = [];
            $recientes = 0;
            
            foreach ($articulos as $articulo) {
                // Contar por categoría
                if (!isset($categorias[$articulo->categoria])) {
                    $categorias[$articulo->categoria] = 0;
                }
                $categorias[$articulo->categoria]++;
                
                // Contar por autor
                if (!isset($autores[$articulo->autor])) {
                    $autores[$articulo->autor] = 0;
                }
                $autores[$articulo->autor]++;
                
                // Contar recientes
                if ($articulo->esReciente()) {
                    $recientes++;
                }
            }
            
            return [
                'total' => $total,
                'recientes' => $recientes,
                'categorias' => $categorias,
                'autores' => $autores,
                'categoria_mas_comun' => array_keys($categorias, max($categorias))[0] ?? 'N/A',
                'autor_mas_prolifico' => array_keys($autores, max($autores))[0] ?? 'N/A'
            ];
        } catch (\Exception $e) {
            error_log('Error al obtener estadísticas: ' . $e->getMessage());
            return [
                'total' => 0,
                'recientes' => 0,
                'categorias' => [],
                'autores' => [],
                'categoria_mas_comun' => 'N/A',
                'autor_mas_prolifico' => 'N/A'
            ];
        }
    }
    
    /**
     * Obtiene el número total de artículos
     * 
     * @return int Número total de artículos
     */
    public function count(): int
    {
        try {
            $db = SupabaseService::db();
            $response = $db->from(self::TABLE_NAME)
                ->select('id', ['count' => 'exact'])
                ->execute();
            
            return isset($response->count) ? (int)$response->count : 0;
        } catch (\Exception $e) {
            error_log('Error al contar artículos: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Obtiene artículos paginados
     * 
     * @param int $pagina Número de página
     * @param int $porPagina Artículos por página
     * @return array Array con artículos y metadatos de paginación
     */
    public function paginate(int $pagina = 1, int $porPagina = 5): array
    {
        try {
            $offset = ($pagina - 1) * $porPagina;
            
            $db = SupabaseService::db();
            
            // Obtener total de artículos
            $countResponse = $db->from(self::TABLE_NAME)
                ->select('id', ['count' => 'exact'])
                ->execute();
            
            $total = isset($countResponse->count) ? (int)$countResponse->count : 0;
            $totalPaginas = ceil($total / $porPagina);
            
            // Obtener artículos de la página
            $response = $db->from(self::TABLE_NAME)
                ->select('*')
                ->order('fecha', ['ascending' => false])
                ->range($offset, $offset + $porPagina - 1)
                ->execute();
            
            $articulos = [];
            if (isset($response->data)) {
                foreach ($response->data as $articuloData) {
                    $articulos[] = Articulo::fromArray([
                        'id' => $articuloData['id'],
                        'titulo' => $articuloData['titulo'],
                        'bajada' => $articuloData['bajada'],
                        'contenido' => $articuloData['contenido'],
                        'fecha' => $articuloData['fecha'],
                        'autor' => $articuloData['autor'],
                        'categoria' => $articuloData['categoria'],
                        'imagen' => $articuloData['imagen'] ?? ''
                    ]);
                }
            }
            
            return [
                'articulos' => $articulos,
                'paginacion' => [
                    'pagina_actual' => $pagina,
                    'total_paginas' => $totalPaginas,
                    'total_articulos' => $total,
                    'por_pagina' => $porPagina,
                    'tiene_anterior' => $pagina > 1,
                    'tiene_siguiente' => $pagina < $totalPaginas
                ]
            ];
        } catch (\Exception $e) {
            error_log('Error al paginar artículos: ' . $e->getMessage());
            return [
                'articulos' => [],
                'paginacion' => [
                    'pagina_actual' => 1,
                    'total_paginas' => 0,
                    'total_articulos' => 0,
                    'por_pagina' => $porPagina,
                    'tiene_anterior' => false,
                    'tiene_siguiente' => false
                ]
            ];
        }
    }
}

