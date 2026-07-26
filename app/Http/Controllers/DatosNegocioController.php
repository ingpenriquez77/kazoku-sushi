<?php

namespace App\Http\Controllers;

use App\Models\DatosNegocio;
use Illuminate\Http\Request;

class DatosNegocioController extends Controller
{
    public function index()
    {
        $datos = DatosNegocio::first();
        return view('configuracion.fiscal', compact('datos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_comercial' => 'required|max:150',
            'moneda'           => 'required|max:5',
        ]);

        $data = [
            'nombre_comercial' => $request->nombre_comercial,
            'razon_social'     => $request->razon_social,
            'nit_rut'          => $request->nit_rut,
            'telefono'         => $request->telefono,
            'direccion'        => $request->direccion,
            'moneda'           => $request->moneda,
            'mensaje_ticket'   => $request->mensaje_ticket,
        ];

        // updateOrCreate busca un registro existente; si no hay ninguno, lo crea
        $datos = DatosNegocio::first();

        if ($datos) {
            $datos->update($data);
            $mensaje = 'Configuración actualizada correctamente.';
        } else {
            DatosNegocio::create($data);
            $mensaje = 'Configuración guardada correctamente.';
        }

        return redirect()->back()->with('success', $mensaje);
    }

    public function getFiscalApi()
    {
        $datos = DatosNegocio::first();

        if (!$datos) {
            return response()->json([
                'nombre_comercial' => 'KAZOKU SUSHI',
                'mensaje_ticket'   => '¡Gracias por su compra!'
            ]);
        }

        return response()->json($datos);
    }
}
