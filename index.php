<?php
require_once __DIR__ . '/vendor/autoload.php';
// Configuración inicial: desactivar display de errores en producción
error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Configurar cabeceras CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Si es una solicitud OPTIONS, terminar aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Incluir el autoloader
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/src/';
    $directories = [
        'controllers/',
        'models/',
        'services/',
        'repositories/',
        'config/'
    ];

    foreach ($directories as $directory) {
        $file = $base_dir . $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Función para renderizar vistas
function renderView($viewPath, $data = [])
{
    if (!is_array($data)) {
        $data = ['mensaje' => $data];
    }

    // Extraer variables para la vista
    extract($data);

    // Definir la ruta completa de la vista
    $viewFile = __DIR__ . '/src/views/' . $viewPath . '.php';

    if (!file_exists($viewFile)) {
        throw new Exception("Vista no encontrada: " . $viewPath);
    }

    // Iniciar el buffer de salida
    ob_start();
    include $viewFile;
    $contenido = ob_get_clean();

    // Incluir el layout con el contenido
    include __DIR__ . '/src/views/layouts/main.php';
}

// Obtener la ruta solicitada
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = '/GitHub/ticketscompra';
$path = str_replace($base_path, '', $request_uri);
$path = strtok($path, '?');

// Ajustar path para eliminar '/index.php' si se incluye
$path = str_replace('/index.php', '', $path);

// Si es llamada al API, desactivar display_errors para no romper JSON
if (strpos($path, '/api/') === 0) {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Si se solicita previsualización (fragmento) de factura, devolver solo la vista parcial
if (isset($_GET['preview']) && $_GET['preview'] === 'factura' && isset($_GET['id'])) {
    header('Content-Type: text/html; charset=UTF-8');
    $controller = new FacturaController();
    $json = $controller->show($_GET['id']);
    $resp = json_decode($json, true);
    if (empty($resp['success'])) {
        http_response_code(404);
        echo 'Factura no encontrada';
        exit;
    }
    $invoice = $resp['data'];
    // Incluir vista parcial sin layout
    include __DIR__ . '/src/views/facturas/show.php';
    exit;
}

// Enrutamiento básico
try {
    switch ($path) {
        case '':
        case '/':
            header('Location: ' . $base_path . '/tickets/nuevo');
            exit;

        case '/tickets/nuevo':
            $controller = new TicketController();
            $data = $controller->nuevo();
            renderView('tickets/nuevo', array_merge($data, [
                'titulo' => 'Nuevo Ticket'
            ]));
            break;

        case '/tickets/lista':
            $controller = new TicketController();
            $data = $controller->lista();
            renderView('tickets/lista', array_merge($data, [
                'titulo' => 'Lista de Tickets'
            ]));
            break;

        // API endpoints para tickets
        case (preg_match('/^\/api\/tickets/', $path) ? true : false):
            header('Content-Type: application/json');
            $controller = new TicketController();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (preg_match('/^\/api\/tickets\/(\d+)\/detalle$/', $path, $matches)) {
                    echo $controller->verDetalle($matches[1]);
                } elseif (preg_match('/^\/api\/tickets\/(\d+)\/reimprimir$/', $path, $matches)) {
                    echo $controller->reimprimirTicket($matches[1]);
                } elseif (preg_match('/^\/api\/tickets\/(\d+)\/generar-factura$/', $path, $matches)) {
                    echo $controller->generarFactura($matches[1]);
                } elseif (preg_match('/^\/api\/tickets\/disponibles$/', $path)) {
                    echo $controller->getTicketsDisponibles();
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (preg_match('/^\/api\/tickets\/agregar-producto$/', $path)) {
                    echo $controller->agregarProducto();
                } elseif (preg_match('/^\/api\/tickets\/finalizar$/', $path)) {
                    echo $controller->finalizarTicket();
                }
                // La eliminación de productos se maneja en memoria en el front-end
            }
            break;

        case '/productos':
            $controller = new ProductoController();
            renderView('productos/index', [
                'entityName' => 'Producto',
                'entityEndpoint' => 'productos',
                'titulo' => 'Gestión de Productos'
            ]);
            break;

        case '/clientes':
            $controller = new ClienteController();
            renderView('clientes/index', [
                'entityName' => 'Cliente',
                'entityEndpoint' => 'clientes',
                'titulo' => 'Gestión de Clientes'
            ]);
            break;

        case '/empresas':
            $controller = new EmpresaController();
            renderView('empresas/index', [
                'entityName' => 'Empresa',
                'entityEndpoint' => 'empresas',
                'titulo' => 'Gestión de Empresas'
            ]);
            break;

        case (preg_match('/^\/facturas\/(\d+)$/', $path, $matches) ? true : false):
            $controller = new FacturaController();
            // Obtener JSON de la factura y decodificar
            $json = $controller->show($matches[1]);
            $resp = json_decode($json, true);
            if (empty($resp['success'])) {
                throw new Exception($resp['error'] ?? 'Factura no encontrada');
            }
            $invoice = $resp['data'];
            // Renderizar vista de detalle
            renderView('facturas/show', [
                'invoice' => $invoice,
                'titulo' => 'Factura ' . $invoice['numero_factura']
            ]);
            break;

        case '/facturas':
            $controller = new FacturaController();
            renderView('facturas/index', [
                'entityName' => 'Factura',
                'entityEndpoint' => 'facturas',
                'titulo' => 'Gestión de Facturas'
            ]);
            break;

        // API endpoints para clientes
        case (preg_match('/^\/api\/clientes/', $path) ? true : false):
            header('Content-Type: application/json');
            $controller = new ClienteController();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (preg_match('/^\/api\/clientes\/(\d+)$/', $path, $matches)) {
                    echo $controller->show($matches[1]);
                } elseif (preg_match('/^\/api\/clientes\/buscar\/(.+)$/', $path, $matches)) {
                    echo $controller->buscar(['dni_cif' => $matches[1]]);
                } else {
                    echo $controller->index();
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo $controller->store($data);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                if (preg_match('/^\/api\/clientes\/(\d+)$/', $path, $matches)) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo $controller->update($matches[1], $data);
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                if (preg_match('/^\/api\/clientes\/(\d+)$/', $path, $matches)) {
                    echo $controller->destroy($matches[1]);
                }
            }
            break;

        // API endpoints para empresas
        case (preg_match('/^\/api\/empresas/', $path) ? true : false):
            header('Content-Type: application/json');
            $controller = new EmpresaController();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (preg_match('/^\/api\/empresas\/(\d+)$/', $path, $matches)) {
                    echo $controller->show($matches[1]);
                } else {
                    echo $controller->index();
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo $controller->store($data);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                if (preg_match('/^\/api\/empresas\/(\d+)$/', $path, $matches)) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo $controller->update($matches[1], $data);
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                if (preg_match('/^\/api\/empresas\/(\d+)$/', $path, $matches)) {
                    echo $controller->destroy($matches[1]);
                }
            }
            break;

        // API endpoints para productos
        case (preg_match('/^\/api\/productos/', $path) ? true : false):
            header('Content-Type: application/json');
            $controller = new ProductoController();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (preg_match('/^\/api\/productos\/(\d+)$/', $path, $matches)) {
                    echo $controller->show($matches[1]);
                } elseif (preg_match('/^\/api\/productos\/buscar$/', $path)) {
                    echo $controller->buscar(['busqueda' => $_GET['q'] ?? '']);
                } else {
                    echo $controller->index();
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                echo $controller->store($data);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                if (preg_match('/^\/api\/productos\/(\d+)$/', $path, $matches)) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo $controller->update($matches[1], $data);
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                if (preg_match('/^\/api\/productos\/(\d+)$/', $path, $matches)) {
                    echo $controller->destroy($matches[1]);
                }
            }
            break;

        // API endpoints para facturas
        case (preg_match('/^\/api\/facturas/', $path) ? true : false):
            header('Content-Type: application/json');
            $controller = new FacturaController();

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (preg_match('/^\/api\/facturas\/(\d+)$/', $path, $matches)) {
                    echo $controller->show($matches[1]);
                } else {
                    echo $controller->index();
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (preg_match('/^\/api\/facturas\/generar\/(\d+)$/', $path, $matches)) {
                    echo $controller->generarFactura($matches[1]);
                }
            }
            break;

        // Rutas para descargar PDF de facturas
        case (preg_match('/^\/facturas\/(\d+)\/pdf$/', $path, $matches) ? true : false):
            $controller = new FacturaController();
            echo $controller->descargarPDF($matches[1]);
            break;

        case '/informes':
            $controller = new InformeController();
            $data = $controller->index();
            renderView('informes/index', $data);
            break;

        default:
            header("HTTP/1.0 404 Not Found");
            renderView('shared/404', [
                'titulo' => 'Página no encontrada',
                'mensaje' => 'La página que estás buscando no existe.'
            ]);
            break;
    }
} catch (Exception $e) {
    renderView('shared/error', [
        'titulo' => 'Error',
        'mensaje' => $e->getMessage()
    ]);
}
