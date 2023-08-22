<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('producto.buscador');
    }
    public function create()
    {
        // Generar código al azar y verificar si existe
        function generarCodigo()
        {
            do {
                $codigo = Str::lower(Str::random(4));
            } while (Producto::where('codigo', $codigo)->exists()); // Verifina que sea unico o vuelve a generar
            return $codigo;
        }
        $codigo = generarCodigo();

        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        $proveedores = Provider::orderBy('nombre', 'asc')->get();

        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();


        return view('producto.create', [
            'codigo' => $codigo,
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
            'proveedores' => $proveedores
        ]);
    }
    public function store(Request $request)
    {

        $request->request->add([
            'codigo' => Str::lower($request->codigo),
        ]);




        // Primero tengo que crear el fabircante, la categoria, provider 
        $producto = Producto::create([
            'nombre' => $request->name,
            'codigo' => $request->codigo,
            'categoria_id' => $request->categoria_id,
            'fabricante_id' => $request->fabricante_id,
            'provider_id' => $request->provider_id

        ]);


        Precio::create([
            'precio' => $request->precio,
            'dolar' => $request->dolar,
            'fabricante_id' => $request->fabricante_id,
            'categoria_id' => $request->categoria_id,
            'producto_id' => $producto->id
        ]);


        // Redireccionar a "show producto" con todos sus datos

        return redirect()->route('producto.show', ['producto' => $producto]);


    }
    public function show(Producto $producto)
    {

        $precio = Precio::where('producto_id', $producto->id)->first();

        $fabricante = Fabricante::find($producto->fabricante_id);

        $categoria = Categoria::find($producto->categoria_id);
        $provider = Provider::find($producto->provider_id);





        // Paso el producto consultado en web.php (el routing)

        return view('producto.show', [
            'producto' => $producto,
            'precio' => $precio,
            'fabricante' => $fabricante,
            'categoria' => $categoria,
            'provider' => $provider
        ]);
    }
}
