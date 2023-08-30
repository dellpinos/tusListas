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


        $ganancia = $request->ganancia;
        $ganancia_tipo = '';

        if($ganancia === 'proveedor') {
            $ganancia_tipo = 'proveedor';
            $ganancia_prod = null;
        } elseif ($ganancia === 'categoria') {
            $ganancia_tipo = 'categoria';
            $ganancia_prod = null;
        } else {
            $ganancia_prod = $ganancia;
            $ganancia_tipo = 'producto';
        }
        
        if(!$ganancia) {
            return redirect()->refresh();
        }

        $precio = Precio::create([
            'precio' => $request->precio,
            'dolar' => $request->dolar,
            'fabricante_id' => $request->fabricante_id,
            'categoria_id' => $request->categoria_id
        ]);

        // Primero tengo que crear el fabircante, la categoria, provider 
        $producto = Producto::create([
            'nombre' => $request->name,
            'codigo' => $request->codigo,
            'categoria_id' => $request->categoria_id,
            'fabricante_id' => $request->fabricante_id,
            'provider_id' => $request->provider_id,
            'ganancia_prod' => $ganancia_prod,
            'ganancia_tipo' => $ganancia_tipo,
            'precio_id' => $precio->id
        ]);




        // Redireccionar a "show producto" con todos sus datos

        return redirect()->route('producto.show', ['producto' => $producto]);
    }
    public function show(Producto $producto)
    {

        $producto->increment('contador_show');
        
        $precio = Precio::find($producto->precio_id);
        $fabricante = Fabricante::find($producto->fabricante_id);
        $categoria = Categoria::find($producto->categoria_id);
        $provider = Provider::find($producto->provider_id);
        

        $precio->updated_at = $precio->updated_at->subHours(3);
        // Que ganancia aplica a este producto
        if(!$producto->ganancia_prod) {
            if($producto->ganancia_tipo === 'proveedor') {
                $producto->ganancia = $provider->ganancia;
                $producto->ganancia_tipo = 'proveedor';
            } else {
                $producto->ganancia = $categoria->ganancia;
                $producto->ganancia_tipo = 'categoria';
            }
        } else {
            $producto->ganancia = $producto->ganancia_prod;
            $producto->ganancia_tipo = 'producto';
        }
        
        $producto->venta = $producto->ganancia * ($precio->precio * 1.21);


        // Paso el producto consultado en web.php (el routing)

        return view('producto.show', [
            'producto' => $producto,
            'precio' => $precio,
            'fabricante' => $fabricante,
            'categoria' => $categoria,
            'provider' => $provider
        ]);
    }

    public function edit(Producto $producto)
    {

        $precio = Precio::find($producto->precio_id);
        
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        $proveedores = Provider::orderBy('nombre', 'asc')->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();
        
        
        $precio->updated_at = $precio->updated_at->subHours(3);
        foreach($categorias as $elemento){
            if($producto->categoria_id  === $elemento->id) {
                $categoria = $elemento;
            }
        }
        foreach($proveedores as $elemento){
            if($producto->provider_id  === $elemento->id) {
                $provider = $elemento;
            }
        }
        foreach($fabricantes as $elemento){
            if($producto->fabricante_id  === $elemento->id) {
                $fabricante = $elemento;
            }
        }

        // Que ganancia aplica a este producto
        if(!$producto->ganancia_prod) {
            if($producto->ganancia_tipo === 'proveedor') {
                $producto->ganancia = $provider->ganancia;
                $producto->ganancia_tipo = 'proveedor';
            } else {
                $producto->ganancia = $categoria->ganancia;
                $producto->ganancia_tipo = 'categoria';
            }
        } else {
            $producto->ganancia = $producto->ganancia_prod;
            $producto->ganancia_tipo = 'producto';
        }
        
        $producto->venta = $producto->ganancia * ($precio->precio * 1.21);

        return view('producto.edit', [
            'producto' => $producto,
            'precio' => $precio,
            'fabricante' => $fabricante,
            'categoria' => $categoria,
            'provider' => $provider,
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
            'proveedores' => $proveedores

        ]);


    }
    public function update(Request $request)
    {

        $producto = Producto::find($request->id);
        $precio = Precio::find($producto->precio_id);



        if(number_format($request->precio, 2, '.', '') !== $precio->precio) { // formato decimal
            $precio->increment('contador_update');
        }


        $ganancia = $request->ganancia;
        $ganancia_tipo = '';
        
        if($ganancia === 'proveedor') {
            $ganancia_tipo = 'proveedor';
            $ganancia_prod = null;
        } elseif ($ganancia === 'categoria') {
            $ganancia_tipo = 'categoria';
            $ganancia_prod = null;
        } else {
            $ganancia_prod = $ganancia;
            $ganancia_tipo = 'producto';
        }
        
        if(!$ganancia) {
            return redirect()->refresh();
        }

        $producto->nombre = $request->name;
        $producto->categoria_id = intval($request->categoria_id);
        $producto->fabricante_id = intval($request->fabricante_id);
        $producto->provider_id = intval($request->provider_id);
        $producto->ganancia_prod = $ganancia_prod;
        $producto->ganancia_tipo = $ganancia_tipo;

        $precio->precio = $request->precio;
        $precio->dolar = $request->dolar;

        $producto->save();
        $precio->save();

        return redirect()->route('producto.show', ['producto' => $producto]);

    }
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('buscador');
    }
}
