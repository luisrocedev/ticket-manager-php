---
# 🎤 Guion hablado para el Proyecto ticketscompra

## Introducción

Hola, soy [tu nombre] y en este vídeo voy a presentar mi proyecto ticketscompra, una aplicación desarrollada en PHP para la gestión y generación de tickets de compra y facturas. A lo largo de la presentación, responderé a preguntas técnicas sobre cómo está construido el proyecto y qué decisiones he tomado.
---

## 1. Programación

ticketscompra utiliza PHP para el backend y HTML, CSS y JavaScript para el frontend. Se emplean variables, arrays, clases y funciones para gestionar productos, tickets, usuarios y facturas. El control de errores se realiza con try-catch y validaciones tanto en backend como en frontend.

La estructura sigue el patrón MVC, separando controladores, modelos, vistas y servicios. Se utilizan librerías externas como dompdf para la generación de tickets y facturas en PDF. El flujo de creación de tickets permite seleccionar empresa, cliente (búsqueda por DNI/CIF), y productos, con control automático de stock.

---

## 2. Sistemas Informáticos

El desarrollo se realiza en macOS, pero ticketscompra es compatible con cualquier servidor que soporte PHP. La comunicación se realiza por HTTP. El control de versiones y las copias de seguridad se gestionan con Git. La seguridad se refuerza validando entradas, gestionando sesiones de usuario y roles, y controlando permisos.

---

## 3. Entornos de Desarrollo

Trabajo con Visual Studio Code y extensiones para PHP y JavaScript. Uso Composer para gestionar dependencias y Git para el control de versiones. Refactorizo el código periódicamente y documento todo en markdown y comentarios.

---

## 4. Bases de Datos

ticketscompra utiliza una base de datos SQL para almacenar productos, tickets, usuarios y facturas. El modelo entidad-relación está bien definido y se realizan copias de seguridad periódicas. Se han añadido filtros avanzados y consultas para informes y estadísticas.

---

## 5. Lenguajes de Marcas y Gestión de Información

El frontend está construido con HTML, CSS y JavaScript. Se emplean formularios para la gestión de datos y se valida la información tanto en frontend como en backend. Los datos se gestionan en formato SQL y JSON para algunas exportaciones. La interfaz muestra totales y productos en tiempo real.

---

## 6. Proyecto Intermodular

El objetivo de ticketscompra es facilitar la gestión y generación de tickets de compra y facturas, permitiendo la administración de productos y usuarios. El stack incluye PHP, SQL, HTML, CSS, JavaScript, Composer y dompdf. El desarrollo se ha realizado por módulos: productos, tickets, usuarios, facturas, informes y utilidades.

---

## Cierre

Esto ha sido un resumen del proyecto ticketscompra, mostrando cómo se han abordado los resultados de aprendizaje y las decisiones técnicas. Si tienes dudas o sugerencias, puedes dejar un comentario. ¡Gracias por tu atención!
