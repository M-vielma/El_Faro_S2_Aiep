-- ============================================
-- Script SQL para crear las tablas en Supabase
-- El Faro - Periódico Digital
-- ============================================

-- Tabla de Usuarios
-- Almacena información de los usuarios registrados en el sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    suscrito BOOLEAN DEFAULT FALSE,
    plan VARCHAR(20) DEFAULT 'free' CHECK (plan IN ('free', 'basic', 'premium', 'vip')),
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Índices para la tabla de usuarios
CREATE INDEX IF NOT EXISTS idx_usuarios_email ON usuarios(email);
CREATE INDEX IF NOT EXISTS idx_usuarios_plan ON usuarios(plan);
CREATE INDEX IF NOT EXISTS idx_usuarios_fecha_registro ON usuarios(fecha_registro);

-- Tabla de Artículos
-- Almacena los artículos publicados en el periódico
CREATE TABLE IF NOT EXISTS articulos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    bajada VARCHAR(300) NOT NULL,
    contenido TEXT NOT NULL,
    fecha TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    autor VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    imagen VARCHAR(255),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Índices para la tabla de artículos
CREATE INDEX IF NOT EXISTS idx_articulos_fecha ON articulos(fecha DESC);
CREATE INDEX IF NOT EXISTS idx_articulos_categoria ON articulos(categoria);
CREATE INDEX IF NOT EXISTS idx_articulos_autor ON articulos(autor);

-- Tabla de Mensajes de Contacto
-- Almacena los mensajes enviados a través del formulario de contacto
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    ip VARCHAR(45),
    fecha TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    leido BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Índices para la tabla de mensajes de contacto
CREATE INDEX IF NOT EXISTS idx_mensajes_contacto_fecha ON mensajes_contacto(fecha DESC);
CREATE INDEX IF NOT EXISTS idx_mensajes_contacto_leido ON mensajes_contacto(leido);
CREATE INDEX IF NOT EXISTS idx_mensajes_contacto_email ON mensajes_contacto(email);

-- ============================================
-- Funciones para actualizar updated_at automáticamente
-- ============================================

-- Función para actualizar updated_at en usuarios
CREATE OR REPLACE FUNCTION update_usuarios_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para usuarios
CREATE TRIGGER trigger_update_usuarios_updated_at
    BEFORE UPDATE ON usuarios
    FOR EACH ROW
    EXECUTE FUNCTION update_usuarios_updated_at();

-- Función para actualizar updated_at en artículos
CREATE OR REPLACE FUNCTION update_articulos_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger para artículos
CREATE TRIGGER trigger_update_articulos_updated_at
    BEFORE UPDATE ON articulos
    FOR EACH ROW
    EXECUTE FUNCTION update_articulos_updated_at();

-- ============================================
-- Políticas de Row Level Security (RLS)
-- ============================================

-- Habilitar RLS en todas las tablas
ALTER TABLE usuarios ENABLE ROW LEVEL SECURITY;
ALTER TABLE articulos ENABLE ROW LEVEL SECURITY;
ALTER TABLE mensajes_contacto ENABLE ROW LEVEL SECURITY;

-- Políticas para usuarios
-- Los usuarios pueden leer su propia información
CREATE POLICY "Los usuarios pueden leer su propia información"
    ON usuarios FOR SELECT
    USING (auth.uid() = id);

-- Los usuarios pueden actualizar su propia información
CREATE POLICY "Los usuarios pueden actualizar su propia información"
    ON usuarios FOR UPDATE
    USING (auth.uid() = id);

-- Políticas para artículos
-- Todos pueden leer artículos
CREATE POLICY "Todos pueden leer artículos"
    ON articulos FOR SELECT
    USING (true);

-- Solo usuarios autenticados pueden crear artículos
CREATE POLICY "Usuarios autenticados pueden crear artículos"
    ON articulos FOR INSERT
    WITH CHECK (auth.role() = 'authenticated');

-- Solo usuarios autenticados pueden actualizar artículos
CREATE POLICY "Usuarios autenticados pueden actualizar artículos"
    ON articulos FOR UPDATE
    USING (auth.role() = 'authenticated');

-- Políticas para mensajes de contacto
-- Todos pueden crear mensajes de contacto
CREATE POLICY "Todos pueden crear mensajes de contacto"
    ON mensajes_contacto FOR INSERT
    WITH CHECK (true);

-- Solo usuarios autenticados pueden leer mensajes de contacto
CREATE POLICY "Usuarios autenticados pueden leer mensajes de contacto"
    ON mensajes_contacto FOR SELECT
    USING (auth.role() = 'authenticated');

-- ============================================
-- Datos de ejemplo (opcional)
-- ============================================

-- Insertar artículos de ejemplo
INSERT INTO articulos (titulo, bajada, contenido, fecha, autor, categoria, imagen) VALUES
(
    'Avances en Tecnología: Inteligencia Artificial Revoluciona la Medicina',
    'Los últimos desarrollos en IA están transformando el diagnóstico médico y el tratamiento de enfermedades complejas.',
    'La inteligencia artificial está revolucionando el campo de la medicina de maneras que hace apenas unos años parecían ciencia ficción. Los algoritmos de machine learning pueden ahora analizar imágenes médicas con una precisión que supera a muchos especialistas humanos, detectando cánceres en etapas tempranas y diagnosticando enfermedades raras que antes requerían años de estudio para identificar.

Los hospitales más avanzados del mundo ya están implementando sistemas de IA que pueden procesar miles de radiografías en minutos, identificando patrones que el ojo humano podría pasar por alto. Esta tecnología no solo mejora la precisión del diagnóstico, sino que también reduce significativamente los tiempos de espera para los pacientes.

Además, la IA está siendo utilizada para desarrollar medicamentos personalizados, analizando el perfil genético de cada paciente para crear tratamientos específicos que maximicen la efectividad y minimicen los efectos secundarios.

Sin embargo, los expertos advierten que esta tecnología debe ser implementada de manera ética y responsable, asegurando que complemente, no reemplace, la experiencia y el juicio humano en la práctica médica.',
    NOW() - INTERVAL '1 day',
    'Dr. María González',
    'Tecnología',
    'ia_med.jpg'
),
(
    'Crisis Económica Global: Impacto en los Mercados Emergentes',
    'Los mercados emergentes enfrentan desafíos sin precedentes debido a la volatilidad económica mundial y las tensiones geopolíticas.',
    'La economía global está experimentando una de sus crisis más complejas en décadas, con los mercados emergentes siendo particularmente vulnerables a las fluctuaciones internacionales. Los analistas económicos reportan que las monedas de varios países en desarrollo han perdido valor significativo frente al dólar estadounidense, lo que ha generado inflación y reducido el poder adquisitivo de millones de personas.

Los factores que contribuyen a esta situación incluyen el aumento de las tasas de interés en las economías desarrolladas, la guerra comercial entre las principales potencias, y la incertidumbre política en varias regiones. Los gobiernos de los países afectados están implementando medidas de emergencia para estabilizar sus economías, incluyendo intervenciones en los mercados de divisas y políticas monetarias restrictivas.

Los expertos predicen que esta crisis podría durar varios años, requiriendo una coordinación internacional sin precedentes para resolver los desequilibrios económicos globales. Mientras tanto, las poblaciones más vulnerables son las que más sufren las consecuencias de esta inestabilidad económica.',
    NOW() - INTERVAL '2 days',
    'Carlos Mendoza',
    'Economía',
    'noticia_2.jpeg'
),
(
    'Deportes: La Selección Nacional se Prepara para el Mundial',
    'El equipo nacional inicia su preparación intensiva para el próximo campeonato mundial con nuevas incorporaciones y estrategias renovadas.',
    'La selección nacional de fútbol ha comenzado su fase de preparación más intensiva de los últimos años, con miras al próximo campeonato mundial que se celebrará en seis meses. El cuerpo técnico, liderado por el experimentado entrenador nacional, ha diseñado un programa de entrenamiento que combina trabajo físico, táctico y psicológico para llevar al equipo a su máximo rendimiento.

Las nuevas incorporaciones al plantel incluyen jóvenes promesas que han destacado en las ligas europeas, así como jugadores experimentados que aportan liderazgo y experiencia en competiciones internacionales. El equipo médico ha implementado protocolos de última generación para prevenir lesiones y optimizar la recuperación de los jugadores.

Los aficionados están expectantes ante las próximas presentaciones del equipo, que incluirán una serie de amistosos contra selecciones de primer nivel mundial. El objetivo es claro: llegar al mundial en las mejores condiciones posibles y luchar por el título que el país espera desde hace décadas.',
    NOW() - INTERVAL '3 days',
    'Ana Rodríguez',
    'Deportes',
    'noticia_3.jpeg'
),
(
    'Cultura: Festival Internacional de Cine Celebra su 25° Aniversario',
    'El prestigioso festival de cine internacional conmemora un cuarto de siglo promoviendo el arte cinematográfico y la diversidad cultural.',
    'El Festival Internacional de Cine celebra este año su 25° aniversario con una programación excepcional que incluye más de 200 películas de 50 países diferentes. Este evento cultural, que se ha convertido en uno de los más importantes de la región, continúa su misión de promover el cine independiente y la diversidad cultural a través del séptimo arte.

La edición de aniversario presenta retrospectivas de directores consagrados, estrenos mundiales de películas independientes, y una sección especial dedicada al cine experimental. Los organizadores han preparado también una serie de conferencias y talleres con destacados profesionales de la industria cinematográfica.

El festival no solo es una vitrina para el cine internacional, sino también una plataforma para promover el talento local. Este año, 15 películas nacionales han sido seleccionadas para competir en diferentes categorías, demostrando el crecimiento y la calidad del cine nacional en los últimos años.',
    NOW() - INTERVAL '4 days',
    'Roberto Silva',
    'Cultura',
    'noticia_4.jpeg'
),
(
    'Ciencia: Descubrimiento Revolucionario en Física Cuántica',
    'Científicos logran un avance histórico en el campo de la computación cuántica que podría cambiar la tecnología para siempre.',
    'Un equipo internacional de científicos ha anunciado un descubrimiento revolucionario en el campo de la física cuántica que podría revolucionar la computación y la tecnología tal como la conocemos. Los investigadores han logrado mantener la coherencia cuántica en un sistema de 100 qubits durante más de 10 segundos, un logro que hasta hace poco se consideraba imposible.

Este avance representa un salto cuántico literal en la capacidad de procesamiento de información, prometiendo computadoras que podrían resolver problemas que actualmente tomarían miles de años en las máquinas más potentes del mundo. Las aplicaciones potenciales incluyen el desarrollo de nuevos medicamentos, la optimización de sistemas de transporte, y la resolución de problemas climáticos complejos.

Sin embargo, los científicos advierten que aún hay muchos desafíos técnicos por superar antes de que esta tecnología esté disponible comercialmente. Los próximos años serán cruciales para determinar si estos avances pueden ser escalados y aplicados en el mundo real.',
    NOW() - INTERVAL '5 days',
    'Dra. Elena Vargas',
    'Ciencia',
    'noticia_5.jpeg'
),
(
    'Política: Reforma Educativa Aprobada por Amplia Mayoría',
    'El parlamento aprueba una reforma educativa integral que promete transformar el sistema educativo nacional en los próximos años.',
    'Con una votación de 85 a 12, el parlamento nacional aprobó la reforma educativa más ambiciosa de las últimas décadas, que promete transformar completamente el sistema educativo del país. La nueva legislación incluye aumentos significativos en el presupuesto educativo, mejoras en la formación docente, y la implementación de tecnologías digitales en todas las escuelas públicas.

La reforma establece un plan de 10 años para modernizar la infraestructura educativa, incluyendo la construcción de nuevas escuelas, la renovación de edificios existentes, y la instalación de laboratorios de computación y bibliotecas digitales. Además, se creará un programa de becas para estudiantes de bajos recursos y se implementará un sistema de evaluación docente más riguroso.

Los críticos de la reforma argumentan que los costos son demasiado altos y que el gobierno debería enfocarse en otros problemas más urgentes. Sin embargo, los defensores de la medida sostienen que la inversión en educación es fundamental para el desarrollo económico y social del país a largo plazo.',
    NOW() - INTERVAL '6 days',
    'Miguel Torres',
    'Política',
    'noticia_6.jpeg'
),
(
    'Medio Ambiente: Iniciativa Global para Combatir el Cambio Climático',
    'Países de todo el mundo se unen en una iniciativa sin precedentes para reducir las emisiones de carbono y proteger el planeta.',
    'Representantes de más de 150 países se reunieron esta semana para lanzar una iniciativa global sin precedentes para combatir el cambio climático. La nueva alianza, llamada "Pacto Verde Global", establece objetivos ambiciosos para reducir las emisiones de gases de efecto invernadero en un 50% para el año 2030.

La iniciativa incluye compromisos específicos de cada país participante, así como un fondo internacional de $100 mil millones de dólares para financiar proyectos de energía renovable y tecnologías limpias en países en desarrollo. Los líderes mundiales han calificado esta alianza como "la última oportunidad" para evitar los peores efectos del cambio climático.

Los científicos del clima han elogiado la iniciativa, pero advierten que los compromisos deben traducirse en acciones concretas y medibles. El éxito de este pacto dependerá de la voluntad política de los gobiernos para implementar las políticas necesarias, incluso cuando enfrenten resistencia de sectores económicos tradicionales.',
    NOW() - INTERVAL '7 days',
    'Isabel Morales',
    'Medio Ambiente',
    'noticia_7.jpeg'
),
(
    'Salud: Nuevo Tratamiento para Enfermedades Neurodegenerativas',
    'Investigadores desarrollan una terapia innovadora que podría detener la progresión de enfermedades como Alzheimer y Parkinson.',
    'Un equipo de investigadores médicos ha desarrollado una terapia innovadora que podría revolucionar el tratamiento de enfermedades neurodegenerativas como Alzheimer y Parkinson. El nuevo tratamiento, que combina terapia génica y medicamentos de última generación, ha mostrado resultados prometedores en ensayos clínicos preliminares.

La terapia funciona reparando las conexiones neuronales dañadas y estimulando la regeneración de células cerebrales. En los ensayos realizados con pacientes en etapas tempranas de la enfermedad, se observó una reducción significativa en la progresión de los síntomas y, en algunos casos, incluso una mejora en las funciones cognitivas.

Aunque los resultados son alentadores, los investigadores advierten que aún se necesitan más estudios a largo plazo para confirmar la efectividad y seguridad del tratamiento. Si los resultados se mantienen en ensayos más amplios, esta terapia podría estar disponible para el público en los próximos 3-5 años, ofreciendo esperanza a millones de pacientes y sus familias.',
    NOW() - INTERVAL '8 days',
    'Dr. Fernando Castro',
    'Salud',
    'noticia_8.jpeg'
),
(
    'Tecnología: Avances en Energía Solar Revolucionan el Sector Energético',
    'Nuevas tecnologías solares prometen hacer la energía renovable más accesible y eficiente que nunca.',
    'Los avances en tecnología solar están revolucionando el sector energético mundial, con nuevas innovaciones que prometen hacer la energía renovable más accesible y eficiente que nunca. Los investigadores han desarrollado paneles solares con una eficiencia del 40%, duplicando la capacidad de generación de energía de los sistemas tradicionales.

Estas nuevas tecnologías incluyen células solares de perovskita que son más baratas de producir y más flexibles en su aplicación, así como sistemas de almacenamiento de energía mejorados que pueden mantener la electricidad generada durante períodos más largos. Los expertos predicen que estos avances podrían hacer que la energía solar sea la fuente de energía más barata del mundo en los próximos cinco años.

Los gobiernos de todo el mundo están invirtiendo fuertemente en estas tecnologías, reconociendo su potencial para reducir las emisiones de carbono y crear empleos en el sector de energía limpia. Las empresas de servicios públicos están comenzando a integrar estos sistemas en sus redes, preparándose para una transición completa hacia la energía renovable.',
    NOW() - INTERVAL '9 days',
    'Ing. Carlos Ruiz',
    'Tecnología',
    'noticia_9.jpeg'
)
ON CONFLICT DO NOTHING;

-- ============================================
-- Comentarios en las tablas
-- ============================================

COMMENT ON TABLE usuarios IS 'Tabla de usuarios registrados en el sistema';
COMMENT ON TABLE articulos IS 'Tabla de artículos publicados en el periódico';
COMMENT ON TABLE mensajes_contacto IS 'Tabla de mensajes enviados a través del formulario de contacto';

COMMENT ON COLUMN usuarios.id IS 'Identificador único del usuario (UUID)';
COMMENT ON COLUMN usuarios.nombre IS 'Nombre completo del usuario';
COMMENT ON COLUMN usuarios.email IS 'Correo electrónico único del usuario';
COMMENT ON COLUMN usuarios.password_hash IS 'Hash de la contraseña del usuario';
COMMENT ON COLUMN usuarios.suscrito IS 'Indica si el usuario está suscrito al newsletter';
COMMENT ON COLUMN usuarios.plan IS 'Plan del usuario (free, basic, premium, vip)';
COMMENT ON COLUMN usuarios.fecha_registro IS 'Fecha de registro del usuario';

COMMENT ON COLUMN articulos.id IS 'Identificador único del artículo';
COMMENT ON COLUMN articulos.titulo IS 'Título del artículo';
COMMENT ON COLUMN articulos.bajada IS 'Subtítulo o bajada del artículo';
COMMENT ON COLUMN articulos.contenido IS 'Contenido completo del artículo';
COMMENT ON COLUMN articulos.fecha IS 'Fecha de publicación del artículo';
COMMENT ON COLUMN articulos.autor IS 'Autor del artículo';
COMMENT ON COLUMN articulos.categoria IS 'Categoría del artículo';
COMMENT ON COLUMN articulos.imagen IS 'Ruta de la imagen del artículo';

COMMENT ON COLUMN mensajes_contacto.id IS 'Identificador único del mensaje';
COMMENT ON COLUMN mensajes_contacto.nombre IS 'Nombre del remitente';
COMMENT ON COLUMN mensajes_contacto.email IS 'Correo electrónico del remitente';
COMMENT ON COLUMN mensajes_contacto.mensaje IS 'Contenido del mensaje';
COMMENT ON COLUMN mensajes_contacto.ip IS 'Dirección IP del remitente';
COMMENT ON COLUMN mensajes_contacto.fecha IS 'Fecha de envío del mensaje';
COMMENT ON COLUMN mensajes_contacto.leido IS 'Indica si el mensaje ha sido leído';

-- ============================================
-- Fin del script
-- ============================================

