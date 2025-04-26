<?php

class InformeController
{
    private $ticketService;
    private $productoService;

    public function __construct()
    {
        $this->ticketService = new TicketService();
        $this->productoService = new ProductoService();
    }

    public function index()
    {
        // Por defecto mostramos las estadísticas del mes actual
        $fechaInicio = date('Y-m-01');
        $fechaFin = date('Y-m-t');

        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
            $fechaInicio = $_GET['fecha_inicio'];
            $fechaFin = $_GET['fecha_fin'];
        }

        return [
            'titulo' => 'Informes y Estadísticas',
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'ventas' => $this->obtenerEstadisticasVentas($fechaInicio, $fechaFin),
            'productos' => $this->obtenerEstadisticasProductos($fechaInicio, $fechaFin),
            'metodosPago' => $this->obtenerEstadisticasMetodosPago($fechaInicio, $fechaFin)
        ];
    }

    private function obtenerEstadisticasVentas($fechaInicio, $fechaFin)
    {
        $tickets = $this->ticketService->buscarTickets([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);

        $ventasTotales = 0;
        $ivaTotalRecaudado = 0;
        $numeroTickets = count($tickets);
        $ticketPromedioValor = 0;

        foreach ($tickets as $ticket) {
            $ventasTotales += $ticket->getTotal();
            $ivaTotalRecaudado += $ticket->getIvaTotal();
        }

        $ticketPromedioValor = $numeroTickets > 0 ? $ventasTotales / $numeroTickets : 0;

        return [
            'ventasTotales' => $ventasTotales,
            'ivaTotalRecaudado' => $ivaTotalRecaudado,
            'numeroTickets' => $numeroTickets,
            'ticketPromedioValor' => $ticketPromedioValor
        ];
    }

    private function obtenerEstadisticasProductos($fechaInicio, $fechaFin)
    {
        $tickets = $this->ticketService->buscarTickets([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);

        $productosVendidos = [];
        foreach ($tickets as $ticket) {
            foreach ($ticket->getItems() as $item) {
                $productoId = $item->getProducto()->getId();
                if (!isset($productosVendidos[$productoId])) {
                    $productosVendidos[$productoId] = [
                        'producto' => $item->getProducto(),
                        'cantidad' => 0,
                        'total' => 0
                    ];
                }
                $productosVendidos[$productoId]['cantidad'] += $item->getCantidad();
                $productosVendidos[$productoId]['total'] += $item->getTotal();
            }
        }

        // Ordenar por cantidad vendida
        uasort($productosVendidos, function ($a, $b) {
            return $b['cantidad'] <=> $a['cantidad'];
        });

        return array_slice($productosVendidos, 0, 10); // Top 10 productos
    }

    private function obtenerEstadisticasMetodosPago($fechaInicio, $fechaFin)
    {
        $tickets = $this->ticketService->buscarTickets([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);

        $metodosPago = [];
        foreach ($tickets as $ticket) {
            $metodoPago = $ticket->getMetodoPago();
            if (!isset($metodosPago[$metodoPago])) {
                $metodosPago[$metodoPago] = [
                    'cantidad' => 0,
                    'total' => 0
                ];
            }
            $metodosPago[$metodoPago]['cantidad']++;
            $metodosPago[$metodoPago]['total'] += $ticket->getTotal();
        }

        return $metodosPago;
    }
}
