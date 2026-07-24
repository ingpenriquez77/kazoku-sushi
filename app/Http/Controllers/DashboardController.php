<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $mesActual = date('m');

        $stats = [
            'ventas_mes' => DB::selectOne("
                SELECT SUM(total) as total
                FROM ventas
                WHERE estado = 'pagado'
                AND MONTH(created_at) = ?
            ", [$mesActual])->total ?? 0,

            'nuevos_pedidos' => DB::selectOne("SELECT COUNT(*) as total FROM ventas WHERE estado = 'pendiente'")->total,
            'productos_total' => DB::selectOne("SELECT COUNT(*) as total FROM productos")->total,
            'mesas_activas' => DB::selectOne("SELECT COUNT(DISTINCT mesa) as total FROM ventas WHERE estado = 'pendiente'")->total,
        ];

        $ultimos_pedidos = DB::select("
            SELECT v.*, u.name as mesero
            FROM ventas v
            LEFT JOIN users u ON v.user_id = u.id
            ORDER BY v.created_at DESC
            LIMIT 7
        ");

        $top_productos = DB::select("
            SELECT p.nombre, p.precio, SUM(dv.cantidad) as total_vendido
            FROM detalle_ventas dv
            JOIN productos p ON dv.producto_id = p.id
            GROUP BY p.id, p.nombre, p.precio
            ORDER BY total_vendido DESC
            LIMIT 5
        ");

        return view('dashboard.index', compact('stats', 'ultimos_pedidos', 'top_productos'));
    }
}
