# Aprendizaje sobre el Proyecto de Gestión de Tickets de Compra

## Programación

### 1. Elementos fundamentales del código

En nuestro proyecto usamos:

- **Variables**: para almacenar datos temporales, como `$nombre`, `$precio`.
- **Constantes**: para valores fijos, como `define('DB_HOST', 'localhost');`.
- **Operadores**: aritméticos (`+`, `-`), lógicos (`&&`, `||`), de comparación (`==`, `!=`).
- **Tipos de datos**: enteros (`int`), cadenas (`string`), booleanos (`bool`), arrays.

**Ejemplo:**

```php
$precio = 10.5; // variable tipo float
define('IVA', 0.21); // constante
$total = $precio * (1 + IVA); // operador aritmético
```

---

### 2. Estructuras de control

Usamos:

- **Selección**: `if`, `else`, `switch` para tomar decisiones.
- **Repetición**: `foreach`, `for` para recorrer listas.
- **Saltos**: `break`, `continue` en bucles.

**Ejemplo:**

```php
foreach ($productos as $producto) {
    if ($producto->stock > 0) {
        // ... código ...
    }
}
```

---

### 3. Control de excepciones y gestión de errores

Sí, usamos `try-catch` para capturar errores, sobre todo en acceso a base de datos.

**Ejemplo:**

```php
try {
    $db->query($sql);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
```

---

### 4. Documentación del código

Comentamos el código y usamos docstrings en funciones y clases para explicar su funcionamiento.

**Ejemplo:**

```php
/**
 * Calcula el total de la factura.
 */
function calcularTotal() {
    // ... código ...
}
```

---

### 5. Paradigma aplicado

Usamos **Programación Orientada a Objetos (POO)** porque facilita la organización, reutilización y mantenimiento del código.

---

### 6. Clases y objetos principales

- `Cliente`, `Producto`, `Factura`, `Ticket`, `TicketItem`
- Controladores: gestionan la lógica de cada entidad.
- Repositorios: gestionan el acceso a la base de datos.

**Relación:** Un `Ticket` tiene varios `TicketItem`, cada uno asociado a un `Producto`.

---

### 7. Conceptos avanzados: herencia, polimorfismo, interfaces

- **Herencia**: `BaseCrudController` es la clase base de los controladores.
- **Polimorfismo**: Los controladores heredan y pueden redefinir métodos.
- **Interfaces**: No usamos interfaces explícitas, pero sí clases abstractas.

---

### 8. Gestión de información: archivos e interfaces

- Usamos **ficheros** para importar/exportar datos (por ejemplo, ticketscompra.sql).
- La interacción con el usuario es mediante **vistas web** (HTML/PHP).

---

### 9. Estructuras de datos

- **Arrays**: para listas de productos, clientes, etc.
- No usamos matrices ni colecciones avanzadas porque PHP gestiona bien los arrays.

---

### 10. Técnicas avanzadas

- Usamos **flujos de entrada/salida** para leer y escribir archivos.
- No usamos expresiones regulares complejas.

---

## Sistemas Informáticos

### 1. Características del hardware

- **Desarrollo**: Ordenador personal (CPU Intel/AMD, 8GB RAM mínimo).
- **Producción**: Servidor web (puede ser local o en la nube).

---

### 2. Sistema operativo

- **macOS** para desarrollo (por compatibilidad y facilidad).
- **Linux** o **Windows** para producción, según el servidor.

---

### 3. Configuración de redes

- Usamos red local para desarrollo (localhost).
- En producción, configuramos el servidor web con HTTPS para seguridad.

---

### 4. Copias de seguridad

- Realizamos copias de la base de datos y del código fuente periódicamente.

---

### 5. Integridad y seguridad de datos

- Validamos entradas de usuario.
- Usamos contraseñas seguras para la base de datos.
- Acceso restringido a archivos sensibles.

---

### 6. Usuarios, permisos y accesos

- Configuramos permisos de archivos y carpetas en el sistema operativo.
- Solo el usuario del servidor web puede modificar archivos críticos.

---

### 7. Documentación técnica

- Documentamos la configuración en archivos README.md y comentarios en el código.

---

## Entornos de Desarrollo

### 1. Entorno de desarrollo (IDE)

- Usamos **Visual Studio Code** y **MAMP** para simular el servidor local.

---

### 2. Automatización de tareas

- Automatizamos tareas como la importación de la base de datos y la actualización del repositorio.

---

### 3. Control de versiones

- Usamos **Git** y **GitHub** para gestionar versiones y ramas.

---

### 4. Refactorización

- Mejoramos el código periódicamente, renombrando variables y separando funciones.

---

### 5. Documentación técnica

- Usamos **Markdown** (`README.md`) y comentarios en el código.

---

### 6. Diagramas

- Creamos diagramas de clases y de flujo para planificar la aplicación (en papel o herramientas online).

---

## Bases de Datos

### 1. Sistema gestor

- Usamos **MySQL** por su integración con PHP y facilidad de uso.

---

### 2. Modelo entidad-relación

- Diseñamos tablas para clientes, productos, tickets, facturas, etc., y sus relaciones.

---

### 3. Vistas, procedimientos, disparadores

- No usamos procedimientos ni disparadores avanzados, solo consultas SQL básicas.

---

### 4. Protección y recuperación de datos

- Copias de seguridad y validación de datos antes de insertar en la base de datos.

---

## Lenguajes de Marcas y Gestión de Información

### 1. Estructura de documentos HTML

- Usamos etiquetas semánticas (`<header>`, `<main>`, `<footer>`) y buenas prácticas.

---

### 2. Tecnologías frontend

- **CSS** para estilos.
- **JavaScript** para validaciones simples.

---

### 3. Interacción con el DOM

- Usamos JavaScript para validar formularios antes de enviarlos.

---

### 4. Validación de HTML y CSS

- Validamos con herramientas online (W3C Validator).

---

### 5. Conversión de datos

- Usamos **JSON** para intercambiar datos entre el backend y el frontend en algunas funciones.

---

### 6. Interacción con sistemas de gestión empresarial

- Nuestra aplicación es una **aplicación de gestión empresarial** para la gestión de tickets y facturas.

---

## Proyecto Intermodular

### 1. Objetivo del software

- Facilitar la gestión de tickets de compra, productos, clientes y facturas para una empresa.

---

### 2. Necesidad que cubre

- Automatiza y organiza la gestión de ventas y facturación.

---

### 3. Stack de tecnologías

- **PHP** (backend), **MySQL** (base de datos), **HTML/CSS/JS** (frontend), **GitHub** (control de versiones).

---

### 4. Desarrollo por versiones

- **Versión 1**: Funcionalidad mínima (gestión de tickets y productos).
- **Actualizaciones**: Añadimos gestión de clientes, facturas, informes, mejoras en la interfaz.

---
