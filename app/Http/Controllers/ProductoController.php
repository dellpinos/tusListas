<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Pendiente;
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
        $precio = Precio::orderBy('dolar', 'desc')->first();
        $dolar_pred = '';

        if($precio !== null) { // En caso del primer producto
            $dolar_pred = (old('dolar') === null) ? intval($precio->dolar) : old('dolar');
        }
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        $providers = Provider::orderBy('nombre', 'asc')->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();

        return view('producto.create', [
            'codigo' => $codigo,
            'categorias' => $categorias,
            'fabricantes' => $fabricantes,
            'providers' => $providers,
            'dolar_pred' => $dolar_pred
        ]);
    }
    public function store(Request $request)
    {

        $request->request->add([
            'codigo' => Str::lower($request->codigo),
        ]);

        $desc_dur = 0;
        $stock = 0;
        if($request->desc_duracion) {
            $desc_dur = $request->desc_duracion;
        }
        if($request->stock) {
            $stock = $request->stock;
        }
        
        $ganancia = $request->ganancia;
        $ganancia_tipo = '';

        // ganancia_numero
        if ($ganancia === 'provider') {
            $ganancia_tipo = 'provider';
            $ganancia_prod = null;

        } elseif ($ganancia === 'categoria') {
            $ganancia_tipo = 'categoria';
            $ganancia_prod = null;

        } elseif ($ganancia === 'personalizada' && !empty($request->ganancia_numero)) {
            $ganancia_tipo = 'producto';
            $ganancia_prod = $request->ganancia_numero;
        }

        if(empty($request->ganancia) || (!is_numeric($ganancia_prod) && $ganancia_prod !== null)) { // $ganancia_prod debe ser un numero
            return redirect()->route('buscador')->with('mensaje', "Ganancia no válida");
        }



        $this->validate($request, [
            'codigo' => 'required|string|max:4|min:4|unique:productos',
            'nombre' => 'required|string|max:60|unique:productos',
            'categoria_id' => 'required|integer',
            'fabricante_id' => 'required|integer',
            'provider_id' => 'required|integer',
            'dolar' => 'numeric|required',
            'precio' => 'numeric|required',
            'ganancia' => 'required',
            'stock' => 'integer|nullable',
            "desc_porc" => 'numeric|nullable',
            "desc_duracion" => 'integer|nullable'
        ]);

        if ($request->codigo_fraccionado !== null) {
            // Producto fraccionado

            $this->validate($request, [ // Da error con cualquiera, el problema puede ser la referencia a "this"

                'codigo_fraccionado' => 'required|max:4|min:4|unique:productos,codigo',
                'unidad_fraccion' => 'required|string|max:60', // <<<<<< dice fraccionado!!!!!
                'contenido_total' => 'required|numeric',
                'ganancia_fraccion' => 'required|numeric|between:0.01,9.99'

            ]);
        }

        $precio = Precio::create([
            'precio' => $request->precio,
            'dolar' => $request->dolar,
            'fabricante_id' => intval($request->fabricante_id),
            'categoria_id' => intval($request->categoria_id),
            'provider_id' => intval($request->provider_id),
            'desc_porc' => $request->desc_porc,
            'desc_duracion' => $desc_dur
        ]);
        if($request->desc_porc) {
            $precio->increment('desc_acu');
        }

        // Primero tengo que crear el fabircante, la categoria, provider 
        $producto = Producto::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'categoria_id' => intval($request->categoria_id),
            'fabricante_id' => intval($request->fabricante_id),
            'provider_id' => intval($request->provider_id),
            'ganancia_prod' => $ganancia_prod,
            'ganancia_tipo' => $ganancia_tipo,
            'precio_id' => intval($precio->id),
            'stock' => $stock,
            'unidad_fraccion' => null,
            'contenido_total' => null,
            'ganancia_fraccion' => null
        ]);

        if ($request->codigo_fraccionado !== null) {
            // Producto fraccionado

            Producto::create([
                'nombre' => $request->nombre . " - Fraccionado",
                'codigo' => strtolower($request->codigo_fraccionado),
                'categoria_id' => intval($request->categoria_id),
                'fabricante_id' => intval($request->fabricante_id),
                'provider_id' => intval($request->provider_id),
                'ganancia_prod' => $ganancia_prod,
                'ganancia_tipo' => $ganancia_tipo,
                'precio_id' => intval($precio->id),
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
        if (empty($producto->ganancia_prod)) {
            if ($producto->ganancia_tipo === 'provider') {
                $producto->ganancia = $provider->ganancia;
                $producto->ganancia_tipo = 'proveedor'; // Este cambio es por como se imprime en pantalla
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

        // Cargar datos para los <select>
        $categorias = Categoria::orderBy('nombre', 'asc')->get();
        $providers = Provider::orderBy('nombre', 'asc')->get();
        $fabricantes = Fabricante::orderBy('nombre', 'asc')->get();
        $precio = Precio::find($producto->precio_id);

        // Cargo 1 o 2 productos relacionados a este precio
        $productos = Producto::where('precio_id', $precio->id)->get();
        // En caso de haber otro registro relacionado
        $producto_secundario = '';
        // Este es un producto fraccionado
        $producto_fraccionado = false;

        /** Consulto si existe otro registro relacionado a esta instancia de Precio (existe fraccionado) */

        // Hay 3 opciones:
        // 1_ No es un producto fraccionado y no existe un fraccionado
        // 2_ No es un producto fraccionado y existe un producto relacionado
        // 3_ Si es un producto fraccionado 

        // _1_ Permito crear fraccionado con el checkbox, mismas instrucciones de create (modificar store)
        // _2_ Reemplazo checkbox por boton-enlace (este mismo metodo con un producto, opción 3)
        // _3_ Formulario secundario desplegado para ser modificado, boton-enlace en el precio (este mismo metodo con un producto, opción 2)

        if ($productos->count() > 1) {
            // Existe fraccionado

            if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {

                // Es un producto fraccionado Opt _3
                $producto_fraccionado = true;
                foreach ($productos as $elemento) {
                    if ($elemento->id !== $producto->id) {
                        $producto_secundario = $elemento;
                    }
                }
            } else {

                // No es un producto fraccionado Opt _2
                $producto_fraccionado = false;
                foreach ($productos as $elemento) {
                    if ($elemento->id !== $producto->id) {
                        $producto_secundario = $elemento;
                    }
                }
            }
        }
        // 1_ No es un producto fraccionado y no existe un fraccionado 

        $categoria = '';
        $provider = '';
        $fabricante = '';


        // Consultar categoria, provider y fabricante del producto actual 
        foreach ($categorias as $elemento) {
            if ($producto->categoria_id  === $elemento->id) {
                $categoria = $elemento;
            }
        }
        foreach ($providers as $elemento) {
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
            if ($producto->ganancia_tipo === 'provider') {
                $producto->ganancia = $provider->ganancia;
                $producto->ganancia_tipo = 'provider';
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
            'providers' => $providers,

            'producto_fraccionado' => $producto_fraccionado, // bool
            'producto_secundario' => $producto_secundario // instancia producto

        ]);
    }
    public function update(Request $request)
    {

        $producto = Producto::find($request->id);
        $precio = Precio::find($producto->precio_id);

        // Cargo 1 o 2 productos relacionados a este precio
        $productos = Producto::where('precio_id', $precio->id)->get();

        // En caso de haber otro registro relacionado
        $producto_secundario = '';
        // Este es un producto fraccionado
        $producto_fraccionado = false;
        if ($productos->count() > 1) {
            // Existe fraccionado

            if ($producto->unidad_fraccion !== null && $producto->contenido_total !== null && $producto->ganancia_fraccion !== null) {

                // Es un producto fraccionado Opt _3
                $producto_fraccionado = true;
                foreach ($productos as $elemento) {
                    if ($elemento->id !== $producto->id) {
                        $producto_secundario = $elemento; // secundario es no-fraccionado
                    }
                }
            } else {

                // No es un producto fraccionado Opt _2
                $producto_fraccionado = false;
                foreach ($productos as $elemento) {
                    if ($elemento->id !== $producto->id) {
                        $producto_secundario = $elemento;
                    }
                }
            }
        }
        // 1_ No es un producto fraccionado y no existe un fraccionado 

        // Acumulador
        if (number_format($request->precio, 2, '.', '') !== $precio->precio) { // formato decimal
            $precio->increment('contador_update');
        }

        // Ganancia aplicada
        $ganancia = $request->ganancia;
        $ganancia_tipo = '';

        if ($ganancia === 'provider') {
            $ganancia_tipo = 'provider';
            $ganancia_prod = null;
        } elseif ($ganancia === 'categoria') {
            $ganancia_tipo = 'categoria';
            $ganancia_prod = null;
        } elseif ($ganancia === 'personalizada' && !empty($request->ganancia_numero)) {
            $ganancia_tipo = 'producto';
            $ganancia_prod = $request->ganancia_numero;
        }
        if(empty($request->ganancia) || (!is_numeric($ganancia_prod) && $ganancia_prod !== null)) { // $ganancia_prod debe ser un numero
            return redirect()->route('buscador')->with('mensaje', "Ganancia no válida");
        }

        $this->validate($request, [
            // Validacion de formulario principal
            'codigo' => 'required|string|max:4|min:4|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string|max:60|unique:productos,nombre,' . $producto->id,
            'categoria_id' => 'required|integer',
            'fabricante_id' => 'required|integer',
            'provider_id' => 'required|integer',
            'dolar' => 'numeric|required',
            'precio' => 'numeric|required',
            'ganancia' => 'required'

        ]);

        $precio->categoria_id = intval($request->categoria_id);
        $precio->fabricante_id = intval($request->fabricante_id);
        $precio->provider_id = intval($request->provider_id);
        $precio->precio = $request->precio;
        $precio->dolar = $request->dolar;
        $precio->save();


        if ($producto_secundario === '') {
            // No existe secundario, es un producto normal

            // Almacenar cambios
            $producto->nombre = $request->nombre;
            $producto->categoria_id = intval($request->categoria_id);
            $producto->fabricante_id = intval($request->fabricante_id);
            $producto->provider_id = intval($request->provider_id);
            $producto->ganancia_prod = $ganancia_prod;
            $producto->ganancia_tipo = $ganancia_tipo;

            $producto->save();

        } else {

            if ($producto_fraccionado === false) {
                // Existe secundario, este producto no es fraccionado

                // Si este no es fraccionado debo validar el formulario principal y almacenar la info en ambos productos

                // Almacenar cambios
                $producto->nombre = $request->nombre;
                $producto->categoria_id = intval($request->categoria_id);
                $producto->fabricante_id = intval($request->fabricante_id);
                $producto->provider_id = intval($request->provider_id);
                $producto->ganancia_prod = $ganancia_prod;
                $producto->ganancia_tipo = $ganancia_tipo;

                $producto->save();

                $producto_secundario->nombre = $request->nombre . " - Fraccionado";
                $producto_secundario->categoria_id = intval($request->categoria_id);
                $producto_secundario->fabricante_id = intval($request->fabricante_id);
                $producto_secundario->provider_id = intval($request->provider_id);
                $producto_secundario->ganancia_prod = $ganancia_prod;
                $producto_secundario->ganancia_tipo = $ganancia_tipo;

                $producto_secundario->save();


            } else {
                // Existe secundario, este producto es fraccionado

                // Si este es fraccionado debo validar todos los campos y almacenar solo este producto

                $this->validate($request, [
                    'codigo' => 'required|max:4|min:4|unique:productos,codigo,' . $producto->id,
                    'unidad_fraccion' => 'required|string|max:60',
                    'contenido_total' => 'required|numeric',
                    'ganancia_fraccion' => 'required|numeric', 'between:0.01,9.99'

                ]);

                $producto->nombre = $request->nombre;
                $producto->categoria_id = intval($request->categoria_id);
                $producto->fabricante_id = intval($request->fabricante_id);
                $producto->provider_id = intval($request->provider_id);
                $producto->ganancia_prod = $ganancia_prod;
                $producto->ganancia_tipo = $ganancia_tipo;

                $producto->unidad_fraccion = $request->unidad_fraccion;
                $producto->contenido_total = $request->contenido_total;
                $producto->ganancia_fraccion = $request->ganancia_fraccion;

                $producto->save();
            }
        }

        // El usuario quiere crear un fraccionado
        if ($request->codigo_fraccionado !== null) {

            $this->validate($request, [
                'codigo_fraccionado' => 'required|max:4|min:4|unique:productos,codigo',
                'unidad_fraccion' => 'required|string|max:60',
                'contenido_total' => 'required|numeric',
                'ganancia_fraccion' => 'required|numeric', 'between:0.01,9.99'

            ]);

            // Producto fraccionado
            Producto::create([
                'nombre' => $request->nombre . " - Fraccionado",
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

        return redirect()->route('producto.show', ['producto' => $producto]);
    }
}
