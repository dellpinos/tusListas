<?php

namespace App\Http\Controllers;

use App\Models\Pendiente;
use Illuminate\Http\Request;

class APIPendientes extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'nombre' => 'required|string|min:3|max:60',
            'cantidad' => 'integer|nullable|min:1|max:999999',
            'precio' => 'numeric|required|max:99999999',
            'descuento' => 'numeric|nullable|min:1|max:999',
            'semanas' => 'integer|min:0|max:8'

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

        $id = filter_var($request->id, FILTER_VALIDATE_INT);

        if(!$id) {
            echo json_encode("Algo salió mal :( ");
            return;
        }
        // Eliminar pendiente
        $pendiente = Pendiente::find($id);
        $resultado = $pendiente->delete();

        echo json_encode($resultado);

    }
}
