---
marp: true
theme: gaia
paginate: true
---

# 🗂️ Aprendizaje sobre el Proyecto ticketscompra

---

# Programación

## 1. Elementos fundamentales del código
- Uso de variables, arrays, clases y funciones en PHP.
- Tipos: string, int, array, objeto.
- Ejemplo:
```php
$productos = [];
class Ticket { /* ... */ }
```

---

## 2. Estructuras de control
- Condicionales: if, else, switch.
- Bucles: for, foreach.
- Ejemplo:
```php
foreach ($productos as $producto) {
  // ...
}
```

---

## 3. Control de excepciones y gestión de errores
- Uso de try-catch en PHP para manejar errores de base de datos y lógica.
- Validaciones en frontend y backend.

---

## 4. Documentación del código
- Comentarios en PHP y archivos markdown (README, aprendizaje, guion).

---

## 5. Paradigma aplicado
- Programación orientada a objetos y modular.
- Separación de lógica en controladores, modelos, vistas y servicios.

---

## 6. Clases y objetos principales
- Clases: Ticket, Producto, Usuario.
- Uso de objetos y arrays para gestionar datos.

---

## 7. Conceptos avanzados
- Generación de tickets en PDF con dompdf.
- Gestión de sesiones y autenticación de usuarios.
- Modularidad y reutilización de funciones.

---

## 8. Gestión de información y archivos
- Uso de base de datos SQL.
- Exportación/importación de datos en SQL y JSON.

---

## 9. Estructuras de datos utilizadas
- Arrays y objetos para productos, tickets y usuarios.

---

## 10. Técnicas avanzadas
- Validación de formularios y gestión de sesiones.
- Uso de Composer para dependencias.

---

# Sistemas Informáticos

## 1. Características del hardware
- Desarrollo y pruebas en MacBook (macOS), compatible con cualquier servidor PHP.

---

## 2. Sistema operativo
- Multiplataforma: macOS, Linux, Windows (con XAMPP/MAMP/WAMP).

---

## 3. Configuración de redes
- Acceso por HTTP en red local o internet.

---

## 4. Copias de seguridad
- Uso de Git para control de versiones y backups manuales de la base de datos.

---

## 5. Integridad y seguridad de datos
- Validación de entradas y gestión de sesiones.
- Uso de permisos y autenticación de usuarios.

---

## 6. Usuarios, permisos y accesos
- Gestión de usuarios y roles en la aplicación.

---

## 7. Documentación técnica
- Archivos markdown y comentarios en el código.

---

# Entornos de Desarrollo

## 1. Entorno de desarrollo (IDE)
- Visual Studio Code con extensiones para PHP y SQL.

---

## 2. Automatización de tareas
- Uso de Composer para instalar dependencias.

---

## 3. Control de versiones
- Git y GitHub.

---

## 4. Refactorización
- Mejoras periódicas en la estructura y modularidad del código.

---

## 5. Documentación técnica
- README.md, aprendizaje.md, guion.md.

---

## 6. Diagramas
- Opcional: diagramas de flujo para la arquitectura del sistema.

---

# Bases de Datos

## 1. Sistema gestor
- SQL para almacenamiento de datos.

---

## 2. Modelo entidad-relación
- Tablas: productos, tickets, usuarios.

---

## 3. Funcionalidades avanzadas
- Consultas complejas y generación de informes.

---

## 4. Protección y recuperación de datos
- Backups manuales y control de versiones en Git.

---

# Lenguajes de Marcas y Gestión de Información

## 1. Estructura de HTML
- Uso de etiquetas semánticas en las vistas.

---

## 2. Tecnologías frontend
- HTML, CSS, JavaScript.

---

## 3. Interacción con el DOM
- JS para mostrar tickets y gestionar la interfaz.

---

## 4. Validación de HTML y CSS
- Validadores online y extensiones del IDE.

---

## 5. Conversión de datos (XML, JSON)
- Exportación/importación de datos en JSON y SQL.

---

## 6. Integración con sistemas de gestión
- Posibilidad de integración con otros sistemas mediante exportaciones.

---

# Proyecto Intermodular

## 1. Objetivo del software
- Facilitar la gestión y generación de tickets de compra.

---

## 2. Necesidad o problema que soluciona
- Permite administrar productos, usuarios y tickets de forma eficiente.

---

## 3. Stack de tecnologías
- PHP, SQL, HTML, CSS, JavaScript, Composer, dompdf.

---

## 4. Desarrollo por módulos
- Módulo de productos, tickets, usuarios y utilidades.

---

<style>
section code, section pre {
  font-size: 0.8em;
}
.small-code code, .small-code pre {
  font-size: 0.7em;
}
</style>