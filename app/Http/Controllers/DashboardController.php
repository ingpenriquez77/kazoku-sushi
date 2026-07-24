<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $inicioMes = now()->startOfMonth();
        $finMes = now()->endOfMonth();

        // 1. Estadísticas (Info Boxes)
        $ventasMesTotal = Venta::where('estado', 'pagado')
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->sum('total');

        $stats = [
            'ventas_mes'      => $ventasMesTotal ?? 0,
            'nuevos_pedidos'  => Venta::where('estado', 'pendiente')->count(),
            'productos_total' => Producto::count(),
            'mesas_activas'   => Venta::where('estado', 'pendiente')->distinct('mesa')->count('mesa'),
        ];

        // 2. Órdenes Recientes
        $ultimos_pedidos = Venta::with('user')
            ->latest()
            ->take(7)
            ->get()
            ->map(function ($venta) {
                $venta->mesero = $venta->user->name ?? 'Sin asignar';
                return $venta;
            });

        // 3. Productos más vendidos
        // Agrupamos el historial de detalles pagados en memoria
        $detallesPagados = DetalleVenta::with('producto')
            ->where('estado_item', '!=', 'cancelado')
            ->get();

        $top_productos = $detallesPagados
            ->groupBy('producto_id')
            ->map(function ($items) {
                $primerDetalle = $items->first();
                $producto = $primerDetalle->producto;

                return (object)[
                    'nombre'        => $producto->nombre ?? 'Producto Eliminado',
                    'precio'        => $producto->precio ?? $primerDetalle->precio_unitario,
                    'total_vendido' => $items->sum('cantidad'),
                ];
            })
            ->sortByDesc('total_vendido')
            ->take(5)
            ->values();

        return view('dashboard.index', compact('stats', 'ultimos_pedidos', 'top_productos'));
    }
}
