<?php

namespace App\Http\Controllers;

use App\Models\Pendiente;
use Illuminate\Http\Request;

class APIPendientes extends Controller
{

    public function index ()
    {
        // Pendiente mas antiguo
        dd("Cargar primer pendiente");
    }
    public function create (Request $request)
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
    public function destroy ()
    {
        // Eliminar pendiente
    }
}

