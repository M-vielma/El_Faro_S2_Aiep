# El Faro - Periódico Digital MVC

Sistema web de periódico digital desarrollado con arquitectura MVC en PHP, sin base de datos, utilizando almacenamiento en memoria y sesiones.

## Características

- **Arquitectura MVC** completa con separación de responsabilidades
- **Sistema de autenticación** con registro y login de usuarios
- **Gestión de artículos** con categorías y estadísticas
- **Formulario de contacto** funcional
- **Diseño responsivo** con Bootstrap 5
- **Almacenamiento en memoria** sin base de datos

## Estructura del Proyecto

```
Elfaro_taller_s2/
├── app/
│   ├── Core/           # Núcleo MVC (Router, Controller, View)
│   ├── Controllers/    # Controladores de la aplicación
│   ├── Models/         # Modelos y repositorios
│   └── Helpers/        # Funciones auxiliares
├── config/             # Configuración de la aplicación
├── public/             # Punto de entrada y assets
│   ├── assets/         # CSS, JS, imágenes
│   └── index.php       # Entrada principal
└── views/              # Vistas de la aplicación
    ├── layout/         # Layout principal
    ├── home/           # Página de inicio
    ├── articulos/      # Gestión de artículos
    ├── auth/           # Autenticación
    ├── contacto/       # Formulario de contacto
    └── perfil/         # Perfil de usuario
```

## Tecnologías Utilizadas

- **PHP 8.0+** - Lenguaje de programación
- **Bootstrap 5** - Framework CSS
- **Bootstrap Icons** - Iconografía
- **Apache** - Servidor web (XAMPP)
- **mod_rewrite** - Reescritura de URLs



## Funcionalidades

### Públicas
- **Página de inicio** con artículos destacados
- **Listado de artículos** con paginación
- **Formulario de contacto** funcional
- **Diseño responsivo** para todos los dispositivos

### Autenticación
- **Registro de usuarios** con validación
- **Login/Logout** de usuarios
- **Gestión de perfiles** de usuario
- **Sistema de sesiones** seguro

### Administración
- **Gestión de artículos** en memoria
- **Estadísticas dinámicas** del sitio
- **Sistema de categorías** de artículos

## Configuración

El archivo `config/app.php` contiene la configuración principal:

```php
return [
    'app_name' => 'El Faro',
    'base_url' => '/Elfaro_taller_s2/public/',
    'ui' => 'bootstrap',
    'environment' => 'development'
];
```

## Responsive Design

- **Mobile First** - Diseño optimizado para móviles
- **Bootstrap 5** - Grid system responsivo
- **Navegación adaptativa** - Menú colapsable en móviles
- **Imágenes optimizadas** - Carga eficiente en todos los dispositivos

## Seguridad

- **Protección CSRF** - Tokens en formularios
- **Escape de datos** - Prevención de XSS
- **Validación de entrada** - Sanitización de datos
- **Sesiones seguras** - Gestión de autenticación

## Características Técnicas

- **Sin base de datos** - Almacenamiento en memoria y sesiones
- **Arquitectura MVC** - Separación clara de responsabilidades
- **Routing personalizado** - Sistema de rutas flexible
- **Sistema de vistas** - Templates reutilizables
- **Helpers globales** - Funciones auxiliares

## Soporte

Para soporte técnico o consultas sobre el proyecto, contactar al equipo de desarrollo.

---

**Realizado por:** Cristina Soto, Gaby y Jonaiker Miguel Vielma Garcia