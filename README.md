# ticketscompra

Sistema de gestión y generación de tickets de compra

## Descripción

ticketscompra es una aplicación desarrollada en PHP que permite gestionar productos, usuarios y generar tickets de compra, así como facturas en PDF. El sistema está pensado para comercios o negocios que necesitan llevar un control de ventas, productos y clientes de forma sencilla y eficiente.

## Funcionalidades principales

- Gestión de productos: alta, baja, modificación y control de stock.
- Gestión de tickets de compra: creación, listado, impresión y exportación a PDF.
- Gestión de clientes y usuarios.
- Generación de facturas a partir de tickets.
- Informes de ventas y estadísticas básicas.
- Validación de datos y control de sesiones.

## Tecnologías utilizadas

- PHP (backend)
- MySQL o MariaDB (base de datos)
- HTML, CSS, JavaScript (frontend)
- Composer (gestión de dependencias)
- dompdf (generación de PDF)

## Estructura del proyecto

- `index.php`: punto de entrada principal.
- `src/`: código fuente organizado en:
  - `config/`: configuración de la base de datos y parámetros globales.
  - `controllers/`: lógica de control y flujo de la aplicación.
  - `models/`: modelos de datos (Producto, Ticket, Usuario, etc.).
  - `repositories/`: acceso a datos y consultas SQL.
  - `services/`: lógica de negocio y utilidades.
  - `views/`: vistas y plantillas HTML.
- `vendor/`: dependencias instaladas con Composer (incluye dompdf).
- `aprendizaje.md` y `guion.md`: documentación y guion del proyecto.

## Instalación

1. Clona el repositorio:
   ```sh
   git clone https://github.com/tuusuario/ticketscompra.git
   ```
2. Instala las dependencias con Composer:
   ```sh
   composer install
   ```
3. Importa la base de datos desde el archivo SQL proporcionado (por ejemplo, `ticketscompra (1).sql`).
4. Configura la conexión a la base de datos en `src/config/Database.php`.
5. Configura tu servidor web (Apache, Nginx, MAMP, XAMPP, etc.) para apuntar al directorio del proyecto.

## Uso básico

1. Accede a la aplicación desde tu navegador.
2. Inicia sesión o regístrate como usuario.
3. Gestiona productos, clientes y tickets desde el menú principal.
4. Crea tickets de compra y genera facturas en PDF.
5. Consulta informes y estadísticas desde la sección correspondiente.

## Documentación

- `aprendizaje.md`: explicación técnica y didáctica del proyecto.

## Requisitos

- PHP 7.4 o superior
- Servidor web compatible (Apache, Nginx, MAMP, XAMPP, etc.)
- MySQL o MariaDB
- Composer

## Autor

Luis Rodriguez
