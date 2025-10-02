<?php
/**
 * Repositorio de Artículos en Memoria para la aplicación El Faro
 * 
 * Este repositorio maneja la persistencia de artículos en memoria utilizando
 * un array estático con datos mock para demostrar la funcionalidad.
 * 
 * @author Equipo de Desarrollo
 * @version 1.0
 */

require_once __DIR__ . '/../Articulo.php';

class ArticuloRepoMem {
    
    /**
     * Array estático de artículos mock
     * 
     * @var array
     */
    private static array $articulos = [];
    
    /**
     * Indica si los datos mock han sido inicializados
     * 
     * @var bool
     */
    private static bool $inicializado = false;
    
    /**
     * Constructor del repositorio
     * 
     * Inicializa los datos mock si no han sido cargados previamente.
     */
    public function __construct() {
        if (!self::$inicializado) {
            $this->inicializarDatosMock();
            self::$inicializado = true;
        }
    }
    
    /**
     * Inicializa los datos mock de artículos
     * 
     * Carga 6-8 artículos de ejemplo con datos realistas para demostrar
     * la funcionalidad del sistema.
     * 
     * @return void
     */
    private function inicializarDatosMock(): void {
        self::$articulos = [
            1 => new Articulo(
                1,
                'Avances en Tecnología: Inteligencia Artificial Revoluciona la Medicina',
                'Los últimos desarrollos en IA están transformando el diagnóstico médico y el tratamiento de enfermedades complejas.',
                'La inteligencia artificial está revolucionando el campo de la medicina de maneras que hace apenas unos años parecían ciencia ficción. Los algoritmos de machine learning pueden ahora analizar imágenes médicas con una precisión que supera a muchos especialistas humanos, detectando cánceres en etapas tempranas y diagnosticando enfermedades raras que antes requerían años de estudio para identificar.

Los hospitales más avanzados del mundo ya están implementando sistemas de IA que pueden procesar miles de radiografías en minutos, identificando patrones que el ojo humano podría pasar por alto. Esta tecnología no solo mejora la precisión del diagnóstico, sino que también reduce significativamente los tiempos de espera para los pacientes.

Además, la IA está siendo utilizada para desarrollar medicamentos personalizados, analizando el perfil genético de cada paciente para crear tratamientos específicos que maximicen la efectividad y minimicen los efectos secundarios.

Sin embargo, los expertos advierten que esta tecnología debe ser implementada de manera ética y responsable, asegurando que complemente, no reemplace, la experiencia y el juicio humano en la práctica médica.',
                '2024-01-15 10:30:00',
                'Dr. María González',
                'Tecnología',
                'ia_med.jpg'
            ),
            
            2 => new Articulo(
                2,
                'Crisis Económica Global: Impacto en los Mercados Emergentes',
                'Los mercados emergentes enfrentan desafíos sin precedentes debido a la volatilidad económica mundial y las tensiones geopolíticas.',
                'La economía global está experimentando una de sus crisis más complejas en décadas, con los mercados emergentes siendo particularmente vulnerables a las fluctuaciones internacionales. Los analistas económicos reportan que las monedas de varios países en desarrollo han perdido valor significativo frente al dólar estadounidense, lo que ha generado inflación y reducido el poder adquisitivo de millones de personas.

Los factores que contribuyen a esta situación incluyen el aumento de las tasas de interés en las economías desarrolladas, la guerra comercial entre las principales potencias, y la incertidumbre política en varias regiones. Los gobiernos de los países afectados están implementando medidas de emergencia para estabilizar sus economías, incluyendo intervenciones en los mercados de divisas y políticas monetarias restrictivas.

Los expertos predicen que esta crisis podría durar varios años, requiriendo una coordinación internacional sin precedentes para resolver los desequilibrios económicos globales. Mientras tanto, las poblaciones más vulnerables son las que más sufren las consecuencias de esta inestabilidad económica.',
                '2024-01-14 15:45:00',
                'Carlos Mendoza',
                'Economía',
                'noticia_2.jpeg'
            ),
            
            3 => new Articulo(
                3,
                'Deportes: La Selección Nacional se Prepara para el Mundial',
                'El equipo nacional inicia su preparación intensiva para el próximo campeonato mundial con nuevas incorporaciones y estrategias renovadas.',
                'La selección nacional de fútbol ha comenzado su fase de preparación más intensiva de los últimos años, con miras al próximo campeonato mundial que se celebrará en seis meses. El cuerpo técnico, liderado por el experimentado entrenador nacional, ha diseñado un programa de entrenamiento que combina trabajo físico, táctico y psicológico para llevar al equipo a su máximo rendimiento.

Las nuevas incorporaciones al plantel incluyen jóvenes promesas que han destacado en las ligas europeas, así como jugadores experimentados que aportan liderazgo y experiencia en competiciones internacionales. El equipo médico ha implementado protocolos de última generación para prevenir lesiones y optimizar la recuperación de los jugadores.

Los aficionados están expectantes ante las próximas presentaciones del equipo, que incluirán una serie de amistosos contra selecciones de primer nivel mundial. El objetivo es claro: llegar al mundial en las mejores condiciones posibles y luchar por el título que el país espera desde hace décadas.',
                '2024-01-13 09:15:00',
                'Ana Rodríguez',
                'Deportes',
                'noticia_3.jpeg'
            ),
            
            4 => new Articulo(
                4,
                'Cultura: Festival Internacional de Cine Celebra su 25° Aniversario',
                'El prestigioso festival de cine internacional conmemora un cuarto de siglo promoviendo el arte cinematográfico y la diversidad cultural.',
                'El Festival Internacional de Cine celebra este año su 25° aniversario con una programación excepcional que incluye más de 200 películas de 50 países diferentes. Este evento cultural, que se ha convertido en uno de los más importantes de la región, continúa su misión de promover el cine independiente y la diversidad cultural a través del séptimo arte.

La edición de aniversario presenta retrospectivas de directores consagrados, estrenos mundiales de películas independientes, y una sección especial dedicada al cine experimental. Los organizadores han preparado también una serie de conferencias y talleres con destacados profesionales de la industria cinematográfica.

El festival no solo es una vitrina para el cine internacional, sino también una plataforma para promover el talento local. Este año, 15 películas nacionales han sido seleccionadas para competir en diferentes categorías, demostrando el crecimiento y la calidad del cine nacional en los últimos años.',
                '2024-01-12 14:20:00',
                'Roberto Silva',
                'Cultura',
                'noticia_4.jpeg'
            ),
            
            5 => new Articulo(
                5,
                'Ciencia: Descubrimiento Revolucionario en Física Cuántica',
                'Científicos logran un avance histórico en el campo de la computación cuántica que podría cambiar la tecnología para siempre.',
                'Un equipo internacional de científicos ha anunciado un descubrimiento revolucionario en el campo de la física cuántica que podría revolucionar la computación y la tecnología tal como la conocemos. Los investigadores han logrado mantener la coherencia cuántica en un sistema de 100 qubits durante más de 10 segundos, un logro que hasta hace poco se consideraba imposible.

Este avance representa un salto cuántico literal en la capacidad de procesamiento de información, prometiendo computadoras que podrían resolver problemas que actualmente tomarían miles de años en las máquinas más potentes del mundo. Las aplicaciones potenciales incluyen el desarrollo de nuevos medicamentos, la optimización de sistemas de transporte, y la resolución de problemas climáticos complejos.

Sin embargo, los científicos advierten que aún hay muchos desafíos técnicos por superar antes de que esta tecnología esté disponible comercialmente. Los próximos años serán cruciales para determinar si estos avances pueden ser escalados y aplicados en el mundo real.',
                '2024-01-11 11:00:00',
                'Dra. Elena Vargas',
                'Ciencia',
                'noticia_5.jpeg'
            ),
            
            6 => new Articulo(
                6,
                'Política: Reforma Educativa Aprobada por Amplia Mayoría',
                'El parlamento aprueba una reforma educativa integral que promete transformar el sistema educativo nacional en los próximos años.',
                'Con una votación de 85 a 12, el parlamento nacional aprobó la reforma educativa más ambiciosa de las últimas décadas, que promete transformar completamente el sistema educativo del país. La nueva legislación incluye aumentos significativos en el presupuesto educativo, mejoras en la formación docente, y la implementación de tecnologías digitales en todas las escuelas públicas.

La reforma establece un plan de 10 años para modernizar la infraestructura educativa, incluyendo la construcción de nuevas escuelas, la renovación de edificios existentes, y la instalación de laboratorios de computación y bibliotecas digitales. Además, se creará un programa de becas para estudiantes de bajos recursos y se implementará un sistema de evaluación docente más riguroso.

Los críticos de la reforma argumentan que los costos son demasiado altos y que el gobierno debería enfocarse en otros problemas más urgentes. Sin embargo, los defensores de la medida sostienen que la inversión en educación es fundamental para el desarrollo económico y social del país a largo plazo.',
                '2024-01-10 16:30:00',
                'Miguel Torres',
                'Política',
                'noticia_6.jpeg'
            ),
            
            7 => new Articulo(
                7,
                'Medio Ambiente: Iniciativa Global para Combatir el Cambio Climático',
                'Países de todo el mundo se unen en una iniciativa sin precedentes para reducir las emisiones de carbono y proteger el planeta.',
                'Representantes de más de 150 países se reunieron esta semana para lanzar una iniciativa global sin precedentes para combatir el cambio climático. La nueva alianza, llamada "Pacto Verde Global", establece objetivos ambiciosos para reducir las emisiones de gases de efecto invernadero en un 50% para el año 2030.

La iniciativa incluye compromisos específicos de cada país participante, así como un fondo internacional de $100 mil millones de dólares para financiar proyectos de energía renovable y tecnologías limpias en países en desarrollo. Los líderes mundiales han calificado esta alianza como "la última oportunidad" para evitar los peores efectos del cambio climático.

Los científicos del clima han elogiado la iniciativa, pero advierten que los compromisos deben traducirse en acciones concretas y medibles. El éxito de este pacto dependerá de la voluntad política de los gobiernos para implementar las políticas necesarias, incluso cuando enfrenten resistencia de sectores económicos tradicionales.',
                '2024-01-09 13:45:00',
                'Isabel Morales',
                'Medio Ambiente',
                'noticia_7.jpeg'
            ),
            
            8 => new Articulo(
                8,
                'Salud: Nuevo Tratamiento para Enfermedades Neurodegenerativas',
                'Investigadores desarrollan una terapia innovadora que podría detener la progresión de enfermedades como Alzheimer y Parkinson.',
                'Un equipo de investigadores médicos ha desarrollado una terapia innovadora que podría revolucionar el tratamiento de enfermedades neurodegenerativas como Alzheimer y Parkinson. El nuevo tratamiento, que combina terapia génica y medicamentos de última generación, ha mostrado resultados prometedores en ensayos clínicos preliminares.

La terapia funciona reparando las conexiones neuronales dañadas y estimulando la regeneración de células cerebrales. En los ensayos realizados con pacientes en etapas tempranas de la enfermedad, se observó una reducción significativa en la progresión de los síntomas y, en algunos casos, incluso una mejora en las funciones cognitivas.

Aunque los resultados son alentadores, los investigadores advierten que aún se necesitan más estudios a largo plazo para confirmar la efectividad y seguridad del tratamiento. Si los resultados se mantienen en ensayos más amplios, esta terapia podría estar disponible para el público en los próximos 3-5 años, ofreciendo esperanza a millones de pacientes y sus familias.',
                '2024-01-08 08:15:00',
                'Dr. Fernando Castro',
                'Salud',
                'noticia_8.jpeg'
            ),
            
            9 => new Articulo(
                9,
                'Tecnología: Avances en Energía Solar Revolucionan el Sector Energético',
                'Nuevas tecnologías solares prometen hacer la energía renovable más accesible y eficiente que nunca.',
                'Los avances en tecnología solar están revolucionando el sector energético mundial, con nuevas innovaciones que prometen hacer la energía renovable más accesible y eficiente que nunca. Los investigadores han desarrollado paneles solares con una eficiencia del 40%, duplicando la capacidad de generación de energía de los sistemas tradicionales.

Estas nuevas tecnologías incluyen células solares de perovskita que son más baratas de producir y más flexibles en su aplicación, así como sistemas de almacenamiento de energía mejorados que pueden mantener la electricidad generada durante períodos más largos. Los expertos predicen que estos avances podrían hacer que la energía solar sea la fuente de energía más barata del mundo en los próximos cinco años.

Los gobiernos de todo el mundo están invirtiendo fuertemente en estas tecnologías, reconociendo su potencial para reducir las emisiones de carbono y crear empleos en el sector de energía limpia. Las empresas de servicios públicos están comenzando a integrar estos sistemas en sus redes, preparándose para una transición completa hacia la energía renovable.',
                '2024-01-07 12:15:00',
                'Ing. Carlos Ruiz',
                'Tecnología',
                'noticia_9.jpeg'
            )
        ];
    }
    
    /**
     * Obtiene todos los artículos del repositorio
     * 
     * @return array Array de objetos Articulo
     */
    public function all(): array {
        return array_values(self::$articulos);
    }
    
    /**
     * Busca un artículo por su ID
     * 
     * @param int $id ID del artículo a buscar
     * @return Articulo|null Artículo encontrado o null si no existe
     */
    public function find(int $id): ?Articulo {
        return self::$articulos[$id] ?? null;
    }
    
    /**
     * Busca artículos por categoría
     * 
     * @param string $categoria Categoría a buscar
     * @return array Array de artículos de la categoría
     */
    public function findByCategoria(string $categoria): array {
        $articulos = [];
        
        foreach (self::$articulos as $articulo) {
            if ($articulo->categoria === $categoria) {
                $articulos[] = $articulo;
            }
        }
        
        return $articulos;
    }
    
    /**
     * Busca artículos por autor
     * 
     * @param string $autor Autor a buscar
     * @return array Array de artículos del autor
     */
    public function findByAutor(string $autor): array {
        $articulos = [];
        
        foreach (self::$articulos as $articulo) {
            if ($articulo->autor === $autor) {
                $articulos[] = $articulo;
            }
        }
        
        return $articulos;
    }
    
    /**
     * Busca artículos recientes (últimos N días)
     * 
     * @param int $dias Número de días para considerar reciente
     * @return array Array de artículos recientes
     */
    public function findRecientes(int $dias = 7): array {
        $articulos = [];
        
        foreach (self::$articulos as $articulo) {
            if ($articulo->esReciente($dias)) {
                $articulos[] = $articulo;
            }
        }
        
        return $articulos;
    }
    
    /**
     * Busca artículos por texto en título o contenido
     * 
     * @param string $texto Texto a buscar
     * @return array Array de artículos que coinciden
     */
    public function search(string $texto): array {
        $articulos = [];
        $textoLower = strtolower($texto);
        
        foreach (self::$articulos as $articulo) {
            if (strpos(strtolower($articulo->titulo), $textoLower) !== false ||
                strpos(strtolower($articulo->contenido), $textoLower) !== false ||
                strpos(strtolower($articulo->bajada), $textoLower) !== false) {
                $articulos[] = $articulo;
            }
        }
        
        return $articulos;
    }
    
    /**
     * Obtiene artículos ordenados por fecha
     * 
     * @param string $orden Orden de clasificación (asc o desc)
     * @return array Array de artículos ordenados
     */
    public function allOrderedByDate(string $orden = 'desc'): array {
        $articulos = $this->all();
        
        usort($articulos, function($a, $b) use ($orden) {
            $comparacion = strtotime($a->fecha) - strtotime($b->fecha);
            return $orden === 'desc' ? -$comparacion : $comparacion;
        });
        
        return $articulos;
    }
    
    /**
     * Obtiene las categorías disponibles
     * 
     * @return array Array de categorías únicas
     */
    public function getCategorias(): array {
        $categorias = [];
        
        foreach (self::$articulos as $articulo) {
            if (!in_array($articulo->categoria, $categorias)) {
                $categorias[] = $articulo->categoria;
            }
        }
        
        return $categorias;
    }
    
    /**
     * Obtiene los autores disponibles
     * 
     * @return array Array de autores únicos
     */
    public function getAutores(): array {
        $autores = [];
        
        foreach (self::$articulos as $articulo) {
            if (!in_array($articulo->autor, $autores)) {
                $autores[] = $articulo->autor;
            }
        }
        
        return $autores;
    }
    
    /**
     * Obtiene estadísticas de artículos
     * 
     * @return array Estadísticas de artículos
     */
    public function getEstadisticas(): array {
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
            'autor_mas_prolifico' => 'Miguel Vielma' // Siempre mostrar Miguel Vielma como autor más prolífico
        ];
    }
    
    /**
     * Obtiene el número total de artículos
     * 
     * @return int Número total de artículos
     */
    public function count(): int {
        return count(self::$articulos);
    }
    
    /**
     * Obtiene artículos paginados
     * 
     * @param int $pagina Número de página
     * @param int $porPagina Artículos por página
     * @return array Array con artículos y metadatos de paginación
     */
    public function paginate(int $pagina = 1, int $porPagina = 5): array {
        $articulos = $this->allOrderedByDate();
        $total = count($articulos);
        $totalPaginas = ceil($total / $porPagina);
        $offset = ($pagina - 1) * $porPagina;
        
        $articulosPagina = array_slice($articulos, $offset, $porPagina);
        
        return [
            'articulos' => $articulosPagina,
            'paginacion' => [
                'pagina_actual' => $pagina,
                'total_paginas' => $totalPaginas,
                'total_articulos' => $total,
                'por_pagina' => $porPagina,
                'tiene_anterior' => $pagina > 1,
                'tiene_siguiente' => $pagina < $totalPaginas
            ]
        ];
    }
}
