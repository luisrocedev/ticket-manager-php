# Sistema de Gestión de Tickets y Facturación

## Descripción

Sistema completo para la gestión de tickets de venta y facturación, diseñado específicamente para su futura integración con el PMS Daniya Denia. Esta aplicación permite gestionar todo el proceso de ventas, desde la creación de tickets hasta la generación de facturas, con un enfoque en la usabilidad y la eficiencia.

## Características Principales

### 1. Gestión de Tickets

- Creación de tickets en tiempo real
- Múltiples métodos de pago
- Asociación con clientes
- Control de productos y cantidades
- Impresión de tickets
- Vista detallada de tickets
- Historial completo de ventas

### 2. Gestión de Productos

- Inventario en tiempo real
- Control de precios
- Gestión de IVA por producto
- Códigos de producto únicos
- Control de stock

### 3. Gestión de Clientes

- Base de datos de clientes
- Búsqueda por DNI/CIF
- Historial de compras por cliente
- Datos fiscales para facturación

### 4. Sistema de Facturación

- Generación automática de facturas
- Numeración secuencial
- Exportación a PDF
- Gestión del estado de facturas
- Búsqueda y filtrado de facturas

### 5. Informes y Estadísticas

- Ventas por período
- Productos más vendidos
- Estadísticas de métodos de pago
- Informes de facturación

## Tecnologías Utilizadas

- PHP
- MySQL
- Bootstrap
- JavaScript
- HTML/CSS

## Requisitos del Sistema

- Servidor web Apache/Nginx
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Extensiones PHP:
  - PDO
  - MySQL
  - mbstring
  - json

## Instalación

1. Clonar el repositorio:

```bash
git clone https://github.com/tuusuario/ticketscompra.git
```

2. Importar la base de datos:

```bash
mysql -u root -p < ticketscompra.sql
```

3. Configurar la conexión a la base de datos en `src/config/Database.php`

4. Configurar el servidor web para apuntar al directorio del proyecto

## Estructura del Proyecto

```
├── index.php                 # Punto de entrada principal
├── src/
│   ├── config/              # Configuración de la aplicación
│   ├── controllers/         # Controladores MVC
│   ├── models/             # Modelos de datos
│   ├── repositories/       # Capa de acceso a datos
│   ├── services/          # Lógica de negocio
│   └── views/             # Vistas y plantillas
```

## Uso

### Gestión de Tickets

1. Acceder a "Nuevo Ticket"
2. Seleccionar productos
3. Agregar cliente (opcional)
4. Seleccionar método de pago
5. Finalizar e imprimir ticket

### Facturación

1. Acceder a "Lista de Tickets"
2. Seleccionar ticket(s)
3. Generar factura
4. Imprimir o exportar a PDF

## Integración con PMS Daniya Denia

### Puntos de Integración Futuros

1. **Sistema de Reservas**

   - Cargos directos a habitación
   - Facturación unificada
   - Estado de cuenta del huésped

2. **Gestión de Clientes**

   - Sincronización automática de datos
   - Historial unificado de gastos
   - Sistema de fidelización

3. **Informes Consolidados**

   - Ingresos por departamento
   - Estadísticas globales
   - KPIs personalizados

4. **APIs y Webhooks**
   - Endpoints REST para integración
   - Webhooks para eventos importantes
   - Sincronización en tiempo real

### Beneficios de la Integración

- Gestión centralizada de clientes
- Facturación unificada de servicios
- Reportes consolidados
- Mejor experiencia del cliente
- Reducción de trabajo administrativo

## Mantenimiento

### Respaldos

- Realizar copias de seguridad diarias de la base de datos
- Mantener respaldos de configuraciones personalizadas
- Documentar cambios y personalizaciones

### Actualizaciones

- Revisar actualizaciones de seguridad
- Mantener las dependencias actualizadas
- Seguir el registro de cambios para nuevas versiones

## Licencia

Propietaria - Todos los derechos reservados
