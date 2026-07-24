<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatosNegocioController extends Controller
{
    public function index()
    {
        // Obtenemos el primer registro o un objeto vacío si no existe
        $datos = DB::table('datos_negocio')->first();
        return view('configuracion.fiscal', compact('datos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_comercial' => 'required|max:150',
            'moneda' => 'required|max:5',
        ]);

        $data = [
            'nombre_comercial' => $request->nombre_comercial,
            'razon_social'     => $request->razon_social,
            'nit_rut'          => $request->nit_rut,
            'telefono'         => $request->telefono,
            'direccion'        => $request->direccion,
            'moneda'           => $request->moneda,
            'mensaje_ticket'   => $request->mensaje_ticket,
            'updated_at'       => now(),
        ];

        $existe = DB::table('datos_negocio')->first();

        if ($existe) {
            DB::table('datos_negocio')->where('id', $existe->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('datos_negocio')->insert($data);
            
            return redirect()->back()->with('success', 'Configuración guardada correctamente.');
        }

        return redirect()->back()->with('success', 'Configuración actualizada correctamente.');
    }

    public function getFiscalApi()
    {
        $datos = DB::table('datos_negocio')->first();
        
        if (!$datos) {
            return response()->json([
                'nombre_comercial' => 'KAZOKU SUSHI',
                'mensaje_ticket' => '¡Gracias por su compra!'
            ]);
        }
        
        return response()->json($datos);
    }
}