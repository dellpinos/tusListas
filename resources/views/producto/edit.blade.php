@extends('layouts.dashboard')
@section('titulo')
    {{ $producto->nombre }}
@endsection

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection




@section('contenido')
<div id="contenedor-pendientes">
    {{-- Js --}}
</div>
    @if ($producto_fraccionado)
        <p class="mensaje__info">Hay opciones que no pueden modificarse en un producto 'fraccionado'. Para realizar
            modificaciones, este debe ser eliminado y luego recreado como una versión 'fraccionada' del producto original.
        </p>
    @endif
    <div class="producto-formulario__grid">


        <form action="{{ route('producto.update') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $producto->id }}" id="producto-id" />
            <div class="formulario__campo-contenedor">
                <label for="codigo" class="formulario__label">Código del producto</label>
                <input type="text" id="codigo" name="codigo" readonly
                    class="formulario__campo formulario__campo--codigo @error('codigo') borde__error @enderror"
                    value="{{ strtoupper($producto->codigo) }}">
                @error('codigo')
                    <p class="alerta__error">{{ $message }}</p>
                @enderror
            </div>



            <div class="producto-formulario__contenedor">
                <div class="producto-formulario__contenedor-sm">

                    <div class="formulario__campo-contenedor">
                        <label for="name" class="formulario__label">Nombre del producto</label>
                        <input type="text" id="name" name="nombre" placeholder="Nombre del producto" required
                            @if ($producto_fraccionado) readonly @endif
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('nombre') borde__error @enderror"
                            value="{{ $producto->nombre }}">
                        @error('nombre')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="categoria" class="formulario__label">Categoria</label>
                        <select
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('categoria') borde__error @enderror"
                            id="categoria" name="categoria_id" @if ($producto_fraccionado) disabled @endif>
                            <option value="{{ $categoria->id }}" selected>{{ $categoria->nombre }}</option>

                            @foreach ($categorias as $elemento)
                                <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                            @endforeach

                            @error('categoria')
                                <p class="alerta__error">{{ $message }}</p>
                            @enderror
                        </select>
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="provider" class="formulario__label">Proveedor</label>
                        <select
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('provider') borde__error @enderror"
                            id="provider" name="provider_id" @if ($producto_fraccionado) disabled @endif>

                            <option value="{{ $provider->id }}" selected>{{ $provider->nombre }}</option>

                            @foreach ($providers as $elemento)
                                <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                            @endforeach

                            @error('provider')
                                <p class="alerta__error">{{ $message }}</p>
                            @enderror
                        </select>
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="fabricante" class="formulario__label">Fabricante</label>
                        <select
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('fabricante') borde__error @enderror"
                            id="fabricante" name="fabricante_id" @if ($producto_fraccionado) disabled @endif>

                            <option value="{{ $fabricante->id }}" selected>{{ $fabricante->nombre }}</option>

                            @foreach ($fabricantes as $elemento)
                                <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                            @endforeach

                            @error('fabricante')
                                <p class="alerta__error">{{ $message }}</p>
                            @enderror
                        </select>
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="dolar" class="formulario__label">Cotización dolar Blue
                            (compra)</label>
                        <input type="number" id="dolar" name="dolar" placeholder="0" value="{{ intval($precio->dolar) }}" required
                        @if ($producto_fraccionado) disabled @endif class="formulario__campo  @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('dolar') borde__error @enderror">
                        @error('dolar')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                        <a class="enlace__normal" href="https://dolarhoy.com/cotizaciondolarblue" target="_blank">Consultar
                            Dolar Hoy</a>
                    </div>
                </div>

                <div class="producto-formulario__contenedor-sm">

                    <div class="formulario__campo-contenedor">
                        <label for="precio" class="formulario__label">Precio Costo sin IVA</label>
                        <input type="number" step="any" id="precio" name="precio" placeholder="0" value="{{ $precio->precio }}" required
                        @if ($producto_fraccionado) disabled @endif class="formulario__campo  @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('precio') borde__error @enderror">
                        @error('precio')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="precio-iva" class="formulario__label">Precio Costo con IVA</label>
                        <input type="number" id="precio-iva" placeholder="0" @if ($producto_fraccionado) disabled @endif
                            class="formulario__campo  @if ($producto_fraccionado) formulario__campo--no-activo @endif @error('precio') borde__error @enderror">
                    </div>

                    <label for="ganancia" class="formulario__label">Ganancia</label>
                    <div class="formulario__contenedor-radio" id="contenedor-radios">

                        <label for="ganancia-categoria" class="formulario__label--small">Categoria</label>
                        <input type="radio" value="categoria" name="ganancia" class="cursor-pointer"
                            id="ganancia-categoria" @if ($producto_fraccionado) disabled @endif @if ($producto->ganancia_tipo === 'categoria') checked @endif />

                        <label for="ganancia-provider" class="formulario__label--small">Proveedor</label>
                        <input type="radio" value="provider" name="ganancia" class="cursor-pointer"
                            id="ganancia-provider" @if ($producto_fraccionado) disabled @endif @if ($producto->ganancia_tipo === 'provider') checked @endif />

                        <label for="ganancia-personalizada" class="formulario__label--small">Personalizada</label>
                        <input type="radio" value="personalizada" name="ganancia" class="cursor-pointer"
                            id="ganancia-personalizada" @if ($producto_fraccionado) disabled @endif @if ($producto->ganancia_tipo === 'producto') checked @endif />
                    </div>
                    <div class="formulario__campo-contenedor">
                        <input type="number" step="0.1" min="0" id="ganancia"
                            placeholder="1.2, 1.7, 1.9" value="{{ $producto->ganancia }}" readonly @if ($producto_fraccionado) disabled @endif
                            class=" formulario__campo formulario__campo--no-activo  @error('ganancia') borde__error @enderror">
                        @error('ganancia')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" id="ganancia-numero" name="ganancia_numero" value="">

                    <div class="formulario__campo-contenedor">
                        <label for="precio" class="formulario__label">Precio Venta</label>
                        <div class="producto-formulario__venta">
                            <input type="number" id="precio-venta" placeholder="0" readonly
                                class="formulario__campo producto-formulario__venta-campo formulario__campo--no-activo ">
                            <a id="btn-venta" class="producto-formulario__venta-boton">Calcular Precio</a>
                        </div>
                    </div>

                    <div class="producto-formulario__flex producto-formulario__contenedor-checkbox">

                        @if ($producto_secundario !== '' && $producto_fraccionado)
                            <a href="{{ route('producto.show', $producto_secundario) }}"
                                class="producto-formulario__venta-boton">Producto No Fraccionado</a>
                        @elseif ($producto_secundario !== '' && !$producto_fraccionado)
                            <a href="{{ route('producto.show', $producto_secundario) }}"
                                class="producto-formulario__venta-boton">Producto Fraccionado</a>
                        @else
                            <input type="checkbox" id="check-fraccion"
                                class="  @error('ganancia') borde__error @enderror">
                        @endif

                        <label for="check-fraccion" class="formulario__label">Venta fraccionado</label>

                    </div>

                </div>
            </div>


            <div id="producto-contenedor-oculto"
                class="@if (!$producto_fraccionado) producto-formulario__contenedor-oculto @endif">
                {{-- <div> Contenedor con hight:0 --}}

                <div class="formulario__campo-contenedor">
                    <label for="codigo" class="formulario__label">Código del Producto Fraccionado</label>

                    <input type="text" id="codigo-fraccionado"
                        @if (!$producto_fraccionado) name="codigo_fraccionado" @endif readonly
                        class="formulario__campo formulario__campo--codigo @error('codigo') borde__error @enderror"
                        value="@if ($producto_fraccionado) {{ strtoupper($producto->codigo) }} @endif">
                    @error('codigo')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                

                <div class="producto-formulario__flex"> {{-- <<< Definir esta clase --}}

                    <div class="formulario__campo-contenedor width-full">
                        <label for="unidad-fraccion" class="formulario__label">Unidad del Producto</label>
                        <input type="text" id="unidad-fraccion" name="unidad_fraccion"
                            placeholder="blister, frasco, kg, unidad, etc" value="{{ $producto->unidad_fraccion }}" @if ($producto_fraccionado) required @endif
                            class="formulario__campo @error('unidad-fraccion') borde__error @enderror">
                        @error('unidad-fraccion')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor width-full">
                        <label for="contenido-total" class="formulario__label ">Total de Unidades</label>
                        <input type="number" id="contenido-total" name="contenido_total" placeholder="3, 25, 500"
                            value="{{ $producto->contenido_total }}" @if ($producto_fraccionado) required @endif
                            class="formulario__campo  @error('contenido-total') borde__error @enderror">
                        @error('contenido-total')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor width-full">
                        <label for="ganancia-fraccion" class="formulario__label">Ganancia Extra Fracción</label>
                        <input type="number" id="ganancia-fraccion" step="any" name="ganancia_fraccion"
                            placeholder="1.1, 1.2, 1.4" value="{{ $producto->ganancia_fraccion }}" @if ($producto_fraccionado) required @endif
                            class="formulario__campo  @error('ganancia-fraccion') borde__error @enderror">
                        @error('ganancia-fraccion')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                </div> {{-- Fin contenedor Flex --}}



                <div class="formulario__campo-contenedor">
                    <label for="precio-fraccionado" class="formulario__label">Precio Venta Fraccionado</label>
                    <div class="producto-formulario__venta">
                        <input type="number" id="precio-fraccionado" placeholder="0" readonly
                            class="formulario__campo producto-formulario__venta-campo formulario__campo--no-activo ">
                        <a id="btn-fraccionado" class="producto-formulario__venta-boton">Calcular Precio Fraccionado</a>
                    </div>
                </div>



            </div> {{-- Fin contenedor oculto --}}
            <input type="submit" value="Guardar Cambios" class="formulario__boton">
        </form>


{{--         <form method="POST" action="{{ route('producto.destroy', $producto) }}">
            @csrf
            @method('DELETE')
            <input type="submit" value="Eliminar Producto" class="formulario__boton formulario__boton--rojo">
        </form> --}}

        <button id="producto-destroy" class="formulario__boton formulario__boton--rojo">Eliminar Producto</button>

        
    </div>
@endsection
