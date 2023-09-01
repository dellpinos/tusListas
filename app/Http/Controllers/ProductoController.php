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

        $codigo = generarCodigo(); // helper

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

        if ($ganancia === 'proveedor') {
            $ganancia_tipo = 'proveedor';
            $ganancia_prod = null;
        } elseif ($ganancia === 'categoria') {
            $ganancia_tipo = 'categoria';
            $ganancia_prod = null;
        } else {
            $ganancia_prod = $ganancia;
            $ganancia_tipo = 'producto';
        }


        if (!$ganancia) {
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
            'precio_id' => $precio->id,
            'unidad_fraccion' => null,
            'contenido_total' => null,
            'ganancia_fraccion' => null
        ]);

        if ($request->codigo_fraccionado !== null) {
            // Producto fraccionado
            Producto::create([
                'nombre' => $request->name . " - Fraccionado",
                'codigo' => strtolower($request->codigo_fraccionado),
                'categoria_id' => $request->categoria_id,
                'fabricante_id' => $request->fabricante_id,
                'provider_id' => $request->provider_id,
                'ganancia_prod' => $ganancia_prod,
                'ganancia_tipo' => $ganancia_tipo,
                'precio_id' => $precio->id,
                'unidad_fraccion' => $request->unidad_fraccion,
                'contenido_total' => $request->contenido_total,
                'ganancia_fraccion' => $request->ganancia_fraccion
            ]);
        }

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
        if (!$producto->ganancia_prod) {
            if ($producto->ganancia_tipo === 'proveedor') {
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
        $producto->venta = redondear($producto->venta);


        // Producto fraccionado
        if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {
            $producto->venta = $producto->ganancia * ($precio->precio * 1.21);
            $producto->venta = ($producto->venta * $producto->ganancia_fraccion) / $producto->contenido_total;
            $producto->venta = redondear($producto->venta);
        }

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

        // Consulto si existe otro registro relacionado a esta instancia de Precio (existe fraccionado)
        $productos = Producto::where('precio_id', $precio->id)->get();

        $producto_secundario = '';
        $producto_fraccionado = false;

        // Hay 3 opciones:
        // 1_ Si no es un producto fraccionado y no existe el mismo voy a permitir crearlo con el checkbox
        // 2_ Si no es un producto fraccionado y existe un producto relacionado voy a reemplazar el checkbox por un enlace
        // 3_ Si es un producto fraccionado voy a desplegar el segundo formulario para que pueda ser modificado

        // _1_ voy a permitir crearlo con el checkbox, mismas instrucciones de create (modificar store)

        // _2_ reemplazo checkbox por boton-enlace (este mismo metodo con otro $producto)

        // _3_ formulario desplegado para ser modificado, boton-enlace en el precio (este mismo metodo con otro $producto)

        if ($productos->count() > 1) {

            // Existe fraccionado
            if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {
                // Es un producto fraccionado Opt _3_

                $producto_fraccionado = true;
                foreach ($productos as $elemento) {
                    if ($elemento->id !== $producto->id) {
                        $producto_secundario = $elemento;
                    }
                }


            } else {

                // No es un producto fraccionado Opt _2_
                $producto_fraccionado = false;
                foreach ($productos as $elemento) {
                    if ($elemento->id !== $producto->id) {
                        $producto_secundario = $elemento;
                    }
                }
            }

            // dd('Existe fraccionado');


        } 


        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        $proveedores = Provider::orderBy('nombre', 'asc')->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();


        $precio->updated_at = $precio->updated_at->subHours(3);
        foreach ($categorias as $elemento) {
            if ($producto->categoria_id  === $elemento->id) {
                $categoria = $elemento;
            }
        }
        foreach ($proveedores as $elemento) {
            if ($producto->provider_id  === $elemento->id) {
                $provider = $elemento;
            }
        }
        foreach ($fabricantes as $elemento) {
            if ($producto->fabricante_id  === $elemento->id) {
                $fabricante = $elemento;
            }
        }

        // Que ganancia aplica a este producto
        if (!$producto->ganancia_prod) {
            if ($producto->ganancia_tipo === 'proveedor') {
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
            'proveedores' => $proveedores,


            'producto_fraccionado' => $producto_fraccionado,
            'producto_secundario' => $producto_secundario

        ]);
    }
    public function update(Request $request)
    {


        $producto = Producto::find($request->id);
        $precio = Precio::find($producto->precio_id);

        if (number_format($request->precio, 2, '.', '') !== $precio->precio) { // formato decimal
            $precio->increment('contador_update');
        }

        $ganancia = $request->ganancia;
        $ganancia_tipo = '';

        if ($ganancia === 'proveedor') {
            $ganancia_tipo = 'proveedor';
            $ganancia_prod = null;
        } elseif ($ganancia === 'categoria') {
            $ganancia_tipo = 'categoria';
            $ganancia_prod = null;
        } else {
            $ganancia_prod = $ganancia;
            $ganancia_tipo = 'producto';
        }

        if (!$ganancia) {
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

        if ($request->codigo_fraccionado !== null) {
            // Producto fraccionado
            $producto_secundario = Producto::create([
                'nombre' => $request->name . " - Fraccionado",
                'codigo' => strtolower($request->codigo_fraccionado),
                'categoria_id' => $request->categoria_id,
                'fabricante_id' => $request->fabricante_id,
                'provider_id' => $request->provider_id,
                'ganancia_prod' => $ganancia_prod,
                'ganancia_tipo' => $ganancia_tipo,
                'precio_id' => $precio->id,
                'unidad_fraccion' => $request->unidad_fraccion,
                'contenido_total' => $request->contenido_total,
                'ganancia_fraccion' => $request->ganancia_fraccion
            ]);
            $producto_secundario->save();
        }

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
