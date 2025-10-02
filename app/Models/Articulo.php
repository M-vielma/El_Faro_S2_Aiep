<?php
/**
 * Modelo Articulo para la aplicación El Faro
 * 
 * Este modelo representa un artículo del periódico con todas sus propiedades
 * y métodos necesarios para el manejo de datos de artículos.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

class Articulo {
    
    /**
     * ID único del artículo
     * 
     * @var int
     */
    public int $id;
    
    /**
     * Título del artículo
     * 
     * @var string
     */
    public string $titulo;
    
    /**
     * Bajada o subtítulo del artículo
     * 
     * @var string
     */
    public string $bajada;
    
    /**
     * Contenido completo del artículo
     * 
     * @var string
     */
    public string $contenido;
    
    /**
     * Fecha de publicación del artículo
     * 
     * @var string
     */
    public string $fecha;
    
    /**
     * Autor del artículo
     * 
     * @var string
     */
    public string $autor;
    
    /**
     * Categoría del artículo
     * 
     * @var string
     */
    public string $categoria;
    
    /**
     * Imagen del artículo
     * 
     * @var string
     */
    public string $imagen;
    
    /**
     * Constructor del modelo Articulo
     * 
     * Inicializa todas las propiedades del artículo con los valores proporcionados.
     * 
     * @param int $id ID único del artículo
     * @param string $titulo Título del artículo
     * @param string $bajada Bajada o subtítulo del artículo
     * @param string $contenido Contenido completo del artículo
     * @param string $fecha Fecha de publicación
     * @param string $autor Autor del artículo
     * @param string $categoria Categoría del artículo
     * @param string $imagen Imagen del artículo
     */
    public function __construct(
        int $id,
        string $titulo,
        string $bajada,
        string $contenido,
        string $fecha = '',
        string $autor = 'Redacción El Faro',
        string $categoria = 'General',
        string $imagen = ''
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->bajada = $bajada;
        $this->contenido = $contenido;
        $this->fecha = $fecha ?: date('Y-m-d H:i:s');
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->imagen = $imagen;
    }
    
    /**
     * Obtiene un resumen del artículo (primeros caracteres del contenido)
     * 
     * @param int $longitud Longitud máxima del resumen
     * @return string Resumen del artículo
     */
    public function getResumen(int $longitud = 150): string {
        if (strlen($this->contenido) <= $longitud) {
            return $this->contenido;
        }
        
        return substr($this->contenido, 0, $longitud) . '...';
    }
    
    /**
     * Obtiene la fecha formateada del artículo
     * 
     * @param string $formato Formato de fecha (por defecto d/m/Y)
     * @return string Fecha formateada
     */
    public function getFechaFormateada(string $formato = 'd/m/Y'): string {
        return date($formato, strtotime($this->fecha));
    }
    
    /**
     * Obtiene la fecha y hora formateada del artículo
     * 
     * @return string Fecha y hora formateada
     */
    public function getFechaHoraFormateada(): string {
        return date('d/m/Y H:i', strtotime($this->fecha));
    }
    
    /**
     * Verifica si el artículo es reciente (publicado en los últimos días)
     * 
     * @param int $dias Número de días para considerar reciente
     * @return bool True si es reciente, false en caso contrario
     */
    public function esReciente(int $dias = 7): bool {
        $fechaArticulo = new DateTime($this->fecha);
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->diff($fechaArticulo);
        
        return $diferencia->days <= $dias;
    }
    
    /**
     * Obtiene la edad del artículo en días
     * 
     * @return int Edad en días
     */
    public function getEdadEnDias(): int {
        $fechaArticulo = new DateTime($this->fecha);
        $fechaActual = new DateTime();
        $diferencia = $fechaActual->diff($fechaArticulo);
        
        return $diferencia->days;
    }
    
    /**
     * Obtiene la edad del artículo en formato legible
     * 
     * @return string Edad en formato legible
     */
    public function getEdadLegible(): string {
        $dias = $this->getEdadEnDias();
        
        if ($dias === 0) {
            return 'Hoy';
        } elseif ($dias === 1) {
            return 'Ayer';
        } elseif ($dias < 7) {
            return "Hace {$dias} días";
        } elseif ($dias < 30) {
            $semanas = floor($dias / 7);
            return $semanas === 1 ? 'Hace 1 semana' : "Hace {$semanas} semanas";
        } elseif ($dias < 365) {
            $meses = floor($dias / 30);
            return $meses === 1 ? 'Hace 1 mes' : "Hace {$meses} meses";
        } else {
            $años = floor($dias / 365);
            return $años === 1 ? 'Hace 1 año' : "Hace {$años} años";
        }
    }
    
    /**
     * Obtiene los datos del artículo como array asociativo
     * 
     * @return array Datos del artículo
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'bajada' => $this->bajada,
            'contenido' => $this->contenido,
            'fecha' => $this->fecha,
            'autor' => $this->autor,
            'categoria' => $this->categoria
        ];
    }
    
    /**
     * Crea una instancia de Articulo desde un array de datos
     * 
     * @param array $data Datos del artículo
     * @return Articulo Instancia del artículo
     */
    public static function fromArray(array $data): Articulo {
        return new Articulo(
            $data['id'] ?? 0,
            $data['titulo'] ?? '',
            $data['bajada'] ?? '',
            $data['contenido'] ?? '',
            $data['fecha'] ?? date('Y-m-d H:i:s'),
            $data['autor'] ?? 'Redacción El Faro',
            $data['categoria'] ?? 'General'
        );
    }
    
    /**
     * Obtiene información pública del artículo (sin contenido completo)
     * 
     * @return array Información pública del artículo
     */
    public function getInfoPublica(): array {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'bajada' => $this->bajada,
            'resumen' => $this->getResumen(),
            'fecha' => $this->fecha,
            'fechaFormateada' => $this->getFechaFormateada(),
            'autor' => $this->autor,
            'categoria' => $this->categoria,
            'edad' => $this->getEdadLegible(),
            'esReciente' => $this->esReciente()
        ];
    }
    
    /**
     * Valida los datos del artículo
     * 
     * @return array Array de errores de validación (vacío si no hay errores)
     */
    public function validar(): array {
        $errores = [];
        
        // Validar título
        if (empty($this->titulo)) {
            $errores['titulo'] = 'El título es requerido';
        } elseif (strlen($this->titulo) < 5) {
            $errores['titulo'] = 'El título debe tener al menos 5 caracteres';
        } elseif (strlen($this->titulo) > 200) {
            $errores['titulo'] = 'El título no puede tener más de 200 caracteres';
        }
        
        // Validar bajada
        if (empty($this->bajada)) {
            $errores['bajada'] = 'La bajada es requerida';
        } elseif (strlen($this->bajada) < 10) {
            $errores['bajada'] = 'La bajada debe tener al menos 10 caracteres';
        } elseif (strlen($this->bajada) > 300) {
            $errores['bajada'] = 'La bajada no puede tener más de 300 caracteres';
        }
        
        // Validar contenido
        if (empty($this->contenido)) {
            $errores['contenido'] = 'El contenido es requerido';
        } elseif (strlen($this->contenido) < 50) {
            $errores['contenido'] = 'El contenido debe tener al menos 50 caracteres';
        }
        
        // Validar autor
        if (empty($this->autor)) {
            $errores['autor'] = 'El autor es requerido';
        } elseif (strlen($this->autor) > 100) {
            $errores['autor'] = 'El autor no puede tener más de 100 caracteres';
        }
        
        // Validar categoría
        if (empty($this->categoria)) {
            $errores['categoria'] = 'La categoría es requerida';
        }
        
        return $errores;
    }
    
    /**
     * Obtiene el número de palabras del contenido
     * 
     * @return int Número de palabras
     */
    public function getNumeroPalabras(): int {
        return str_word_count($this->contenido);
    }
    
    /**
     * Obtiene el tiempo estimado de lectura en minutos
     * 
     * @param int $palabrasPorMinuto Palabras por minuto (por defecto 200)
     * @return int Tiempo estimado en minutos
     */
    public function getTiempoLectura(int $palabrasPorMinuto = 200): int {
        $palabras = $this->getNumeroPalabras();
        $minutos = ceil($palabras / $palabrasPorMinuto);
        return max(1, $minutos); // Mínimo 1 minuto
    }
    
    /**
     * Obtiene una representación en string del artículo
     * 
     * @return string Representación del artículo
     */
    public function __toString(): string {
        return "Artículo [ID: {$this->id}, Título: {$this->titulo}, Categoría: {$this->categoria}, Autor: {$this->autor}]";
    }
}
