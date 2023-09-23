<?php

namespace App\Http\Controllers;

use App\Models\Pendiente;
use Illuminate\Http\Request;

class APIPendientes extends Controller
{

    public function index()
    {
        // Pendiente mas antiguo
        $pendiente = Pendiente::orderBy('created_at', 'asc')->first();

        echo json_encode($pendiente);
    }
    public function count()
    {
        $pendientes = Pendiente::all()->count();

        echo json_encode($pendientes);
    }
    public function create(Request $request)
    {
        // Nuevo pendiente
        $this->validate($request, [
            'nombre' => 'required|string|max:60',
            'cantidad' => 'integer|nullable',
            'precio' => 'numeric|required',
            'descuento' => 'numeric|nullable',
            'semanas' => 'integer',
        ]);
        $pendiente = Pendiente::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'stock' => $request->cantidad,
            'desc_porc' => $request->descuento,
            'desc_duracion' => $request->semanas
        ]);

        echo json_encode($pendiente);
    }
    public function destroy(Request $request)
    {
        // Eliminar pendiente
        $pendiente = Pendiente::find($request->id);
        $resultado = $pendiente->delete();

        echo json_encode($resultado);

    }
}
