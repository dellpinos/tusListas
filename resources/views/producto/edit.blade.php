@extends('layouts.dashboard')
@section('titulo')
    {{ $producto->nombre }}
@endsection

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
    @vite('resources/js/productoForm.js')
@endpush


@section('contenido')
    <div class="producto-formulario__grid">


    <form action="{{ route('producto.update') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $producto->id }}" />
        <div class="formulario__campo-contenedor">
            <label for="codigo" class="formulario__label">Código del producto</label>
            <input type="text" id="codigo" name="codigo" readonly
                class="formulario__campo formulario__campo--codigo @error('codigo') border-red-500 @enderror"
                value="{{ strtoupper($producto->codigo) }}">
            @error('codigo')
                <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
            @enderror
        </div>



        <div class="producto-formulario__contenedor">
            <div class="producto-formulario__contenedor-sm">

                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del producto</label>
                    <input type="text" id="name" name="name" placeholder="Nombre del producto"
                        class="formulario__campo @error('name') border-red-500 @enderror" value="{{ $producto->nombre }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="categoria" class="formulario__label">Categoria</label>
                    <select class="formulario__campo @error('categoria') border-red-500 @enderror" id="categoria"
                        name="categoria_id">
                        <option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}</option>

                        @foreach ($categorias as $elemento)
                            <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                        @endforeach

                        @error('categoria')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="proveedor" class="formulario__label">Proveedor</label>
                    <select class="formulario__campo @error('proveedor') border-red-500 @enderror" id="proveedor"
                        name="provider_id">

                        <option value="{{ $provider->id }}" selected>{{ $provider->nombre }}</option>

                        @foreach ($proveedores as $elemento)
                            <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                        @endforeach

                        @error('proveedor')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="fabricante" class="formulario__label">Laboratorio -
                        Fabricante</label>
                    <select class="formulario__campo @error('fabricante') border-red-500 @enderror" id="fabricante"
                        name="fabricante_id">

                        <option value="{{ $fabricante->id }}" selected>{{ $fabricante->nombre }}</option>

                        @foreach ($fabricantes as $elemento)
                            <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                        @endforeach

                        @error('fabricante')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="dolar" class="formulario__label">Cotización dolar Blue
                        (compra)</label>
                    <input type="number" id="dolar" name="dolar" placeholder="0"
                        value="{{ $precio->dolar }}" class="formulario__campo text-right @error('dolar') border-red-500 @enderror">
                    @error('dolar')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="producto-formulario__contenedor-sm">

                <div class="formulario__campo-contenedor">
                    <label for="precio" class="formulario__label">Precio Costo sin IVA</label>
                    <input type="number" id="precio" name="precio" placeholder="0"
                        value="{{ $precio->precio }}" class="formulario__campo text-right @error('precio') border-red-500 @enderror">
                    @error('precio')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="precio" class="formulario__label">Precio Costo con IVA</label>
                    <input type="number" id="precio-iva" placeholder="0"
                        class="formulario__campo text-right @error('precio') border-red-500 @enderror">
                    @error('precio')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <label for="ganancia" class="formulario__label">Ganancia</label>
                <div class="formulario__contenedor-radio" id="contenedor-radios">

                    <label for="ganancia-categoria" class="formulario__label--small">Categoria</label>
                    <input type="radio" value="categoria" name="ganancia" class="cursor-pointer" id="ganancia-categoria"
                        {{ $producto->ganancia_tipo === 'categoria' ? 'checked' : '' }} />

                    <label for="ganancia-proveedor" class="formulario__label--small">Proveedor</label>
                    <input type="radio" value="proveedor" name="ganancia" class="cursor-pointer"
                        id="ganancia-proveedor" {{ $producto->ganancia_tipo === 'proveedor' ? 'checked' : '' }} />

                    <label for="ganancia-personalizada" class="formulario__label--small">Personalizada</label>
                    <input type="radio" value="personalizada" name="ganancia" class="cursor-pointer"
                        id="ganancia-personalizada" {{ $producto->ganancia_tipo === 'producto' ? 'checked' : '' }} />
                </div>
                <div class="formulario__campo-contenedor">
                    <input type="number" step="0.1" min="0" id="ganancia" name="ganancia"
                        placeholder="1.2, 1.7, 1.9" disabled value="{{ $producto->ganancia }}"
                        class=" formulario__campo formulario__campo--no-activo text-right @error('ganancia') border-red-500 @enderror">
                    @error('ganancia')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="precio" class="formulario__label">Precio Venta</label>
                    <div class="producto-formulario__venta">
                        <input type="number" id="precio-venta" placeholder="0" readonly
                            class="formulario__campo producto-formulario__venta-campo formulario__campo--no-activo text-right">
                        <a id="btn-venta" class="producto-formulario__venta-boton">Calcular Precio</a>
                    </div>
                </div>

                <div class="producto-formulario__flex producto-formulario__contenedor-checkbox">

                    @if($producto_fraccionado || $producto_secundario !== '')
                    
                    <a href="{{route('producto.show', $producto_secundario)}}" class="producto-formulario__venta-boton">Producto No Fraccionado</a>
                    @else
                    <input type="checkbox" id="check-fraccion"
                        class="  @error('ganancia') border-red-500 @enderror">
                    
                        
                    @endif
                    <label for="check-fraccion" class="formulario__label">Venta fraccionado</label>

                </div>

            </div>
        </div>


        <div id="producto-contenedor-oculto" class="@if(!$producto_fraccionado) producto-formulario__contenedor-oculto @endif"> {{-- <div> Contenedor con hight:0 --}}

            <div class="formulario__campo-contenedor">
                <label for="codigo" class="formulario__label">Código del Producto Fraccionado</label>
                
                <input type="text" id="codigo-fraccionado" name="codigo_fraccionado" readonly
                    class="formulario__campo formulario__campo--codigo @error('codigo') border-red-500 @enderror"
                    value="@if($producto_fraccionado) {{ strtoupper($producto->codigo) }}@endif">
                @error('codigo')
                    <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="producto-formulario__flex"> {{-- <<< Definir esta clase --}}

                <div class="formulario__campo-contenedor width-full">
                    <label for="unidad-fraccion" class="formulario__label">Unidad del Producto</label>
                    <input type="text" id="unidad-fraccion" name="unidad_fraccion" placeholder="blister, frasco, ml, kg"
                        value="{{ $producto->unidad_fraccion }}" class="formulario__campo @error('unidad-fraccion') border-red-500 @enderror">
                    @error('unidad-fraccion')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor width-full">
                    <label for="contenido-total" class="formulario__label ">Total de Unidades</label>
                    <input type="number" id="contenido-total" name="contenido_total" placeholder="25, 3, 500"
                    value="{{ $producto->contenido_total }}" class="formulario__campo text-right @error('contenido-total') border-red-500 @enderror">
                    @error('contenido-total')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor width-full">
                    <label for="ganancia-fraccion" class="formulario__label">Ganancia Extra Fracción</label>
                    <input type="number" id="ganancia-fraccion" step="any" name="ganancia_fraccion" placeholder="1.1, 1.2, 1.4"
                    value="{{ $producto->ganancia_fraccion }}" class="formulario__campo text-right @error('ganancia-fraccion') border-red-500 @enderror">
                    @error('ganancia-fraccion')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

            </div> {{-- Fin contenedor Flex --}}



            <div class="formulario__campo-contenedor">
                <label for="precio-fraccionado" class="formulario__label">Precio Venta Fraccionado</label>
                <div class="producto-formulario__venta">
                    <input type="number" id="precio-fraccionado" placeholder="0" readonly
                        class="formulario__campo producto-formulario__venta-campo formulario__campo--no-activo text-right">
                    <a id="btn-fraccionado" class="producto-formulario__venta-boton">Calcular Precio Fraccionado</a>
                </div>
            </div>



        </div> {{-- Fin contenedor oculto --}}
        <input type="submit" value="Guardar Cambios" class="formulario__boton">
    </form>


    <form method="POST" action="{{ route('producto.destroy', $producto)}}">
        @csrf
        @method('DELETE')
        <input type="submit" value="Eliminar Producto" class="formulario__boton formulario__boton--rojo">
    </form>
</div>
@endsection