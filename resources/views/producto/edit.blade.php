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
        <p class="mensaje__info mensaje__warning">Algunas opciones no estan disponibles en un "producto fraccionado".
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

            <div class="producto-formulario__contenedor mb-4">
                <div class="producto-formulario__contenedor-sm">

                    <div class="formulario__campo-contenedor">
                        <label for="name" class="formulario__label">Nombre del producto *</label>
                        <input type="text" id="name" name="nombre" placeholder="Nombre del producto" required
                            maxlength="60" @if ($producto_fraccionado) readonly @endif
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('nombre') borde__error @enderror"
                            value="{{ $producto->nombre }}">
                        @error('nombre')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="categoria" class="formulario__label">Categoria *</label>
                        <select
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('categoria') borde__error @enderror"
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
                        <label for="provider" class="formulario__label">Proveedor *</label>
                        <select
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('provider') borde__error @enderror"
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
                        <label for="fabricante" class="formulario__label">Fabricante *</label>
                        <select
                            class="formulario__campo @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('fabricante') borde__error @enderror"
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
                            (compra) *</label>
                        <input type="number" id="dolar" name="dolar" placeholder="0" min="1"
                            value="{{ intval($precio->dolar) }}" required @if ($producto_fraccionado) disabled @endif
                            class="formulario__campo  @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('dolar') borde__error @enderror">
                        @error('dolar')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                        <a class="enlace__normal" href="https://dolarhoy.com/cotizaciondolarblue" target="_blank">Consultar
                            Dolar Hoy</a>
                    </div>
                    <div class="producto-formulario__flex producto-formulario__contenedor-checkbox">

                        @if ($producto_secundario !== '' && $producto_fraccionado)
                            <a href="{{ route('producto.edit', $producto_secundario) }}"
                                class="producto-formulario__enlace-fraccionado">Ver Producto No-Fraccionado</a>
                        @elseif ($producto_secundario !== '' && !$producto_fraccionado)
                            <a href="{{ route('producto.edit', $producto_secundario) }}"
                                class="producto-formulario__enlace-fraccionado">Ver Producto Fraccionado</a>
                        @else
                            <input type="checkbox" id="check-fraccion" class="  @error('ganancia') borde__error @enderror">
                        

                        <label for="check-fraccion"
                            class="formulario__label pointer
                                                @error('ganancia_fraccion')
                                                c-red
                                                @enderror
                                                @error('contenido_total')
                                                c-red
                                                @enderror
                                                @error('unidad_fraccion')
                                                c-red
                                                @enderror
                                            ">Venta
                            fraccionado</label>
                            @endif
                    </div>
                </div>

                <div class="producto-formulario__contenedor-sm">

                    <div class="formulario__campo-contenedor">
                        <label for="precio" class="formulario__label">Precio Costo sin IVA *</label>
                        <input type="number" step="any" id="precio" name="precio" placeholder="0"
                            value="{{ $precio->precio }}" required @if ($producto_fraccionado) disabled @endif
                            class="formulario__campo  @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('precio') borde__error @enderror">
                        @error('precio')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor">
                        <label for="precio-iva" class="formulario__label">Precio Costo con IVA *</label>
                        <input type="number" id="precio-iva" placeholder="0"
                            @if ($producto_fraccionado) disabled @endif
                            class="formulario__campo  @if ($producto_fraccionado) formulario__campo--no-activo b-yellow @endif @error('precio') borde__error @enderror">
                    </div>

                    <label for="ganancia" class="formulario__label">Ganancia *</label>
                    <div class="formulario__contenedor-radio" id="contenedor-radios">

                        <label for="ganancia-categoria" class="formulario__label--small">Categoria</label>
                        <input type="radio" value="categoria" name="ganancia" class="cursor-pointer"
                            id="ganancia-categoria" @if ($producto_fraccionado) disabled @endif
                            @if ($producto->ganancia_tipo === 'categoria') checked @endif />

                        <label for="ganancia-provider" class="formulario__label--small">Proveedor</label>
                        <input type="radio" value="provider" name="ganancia" class="cursor-pointer"
                            id="ganancia-provider" @if ($producto_fraccionado) disabled @endif
                            @if ($producto->ganancia_tipo === 'provider') checked @endif />

                        <label for="ganancia-personalizada" class="formulario__label--small">Personalizada</label>
                        <input type="radio" value="personalizada" name="ganancia" class="cursor-pointer"
                            id="ganancia-personalizada" @if ($producto_fraccionado) disabled @endif
                            @if ($producto->ganancia_tipo === 'producto') checked @endif />
                    </div>
                    <div class="formulario__campo-contenedor">
                        <input type="number" step="0.01" min="1" max="19.9" id="ganancia"
                            placeholder="1.2, 1.7, 1.9" value="{{ $producto->ganancia }}" readonly
                            @if ($producto_fraccionado) disabled @endif
                            class=" formulario__campo formulario__campo--no-activo @if ($producto_fraccionado) b-yellow @endif @error('ganancia') borde__error @enderror">
                        @error('ganancia')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <input type="hidden" id="ganancia-numero" name="ganancia_numero" value="">

                    <div class="formulario__campo-contenedor">
                        <div class="producto-formulario__venta">
                            <p class="producto-formulario__venta-precio" id="precio-venta">$ ?</p>
                            <a id="btn-venta" class="producto-formulario__venta-boton">
                                <i class="fa-solid fa-rotate-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="producto-contenedor-oculto"
                class="@if (!$producto_fraccionado) producto-formulario__contenedor-oculto @endif">

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

                <div class="producto-formulario__flex">

                    <div class="formulario__campo-contenedor width-full">
                        <label for="unidad-fraccion" class="formulario__label">Unidad del Producto *</label>
                        <input type="text" id="unidad-fraccion" name="unidad_fraccion" maxlength="30"
                            placeholder="blister, frasco, kg, unidad, etc" value="{{ $producto->unidad_fraccion }}"
                            @if ($producto_fraccionado) required @endif
                            class="formulario__campo @error('unidad-fraccion') borde__error @enderror">
                        @error('unidad-fraccion')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor width-full">
                        <label for="contenido-total" class="formulario__label ">Total de Unidades *</label>
                        <input type="number" id="contenido-total" name="contenido_total" placeholder="3, 25, 500" min="1" max="9999"
                            value="{{ $producto->contenido_total }}" @if ($producto_fraccionado) required @endif
                            class="formulario__campo  @error('contenido-total') borde__error @enderror">
                        @error('contenido-total')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="formulario__campo-contenedor width-full">
                        <label for="ganancia-fraccion" class="formulario__label">Ganancia Extra Fracción *</label>
                        <input type="number" id="ganancia-fraccion" step="0.01" min="1" max="19.9"
                            name="ganancia_fraccion" placeholder="1.1, 1.2, 1.4"
                            value="{{ $producto->ganancia_fraccion }}" @if ($producto_fraccionado) required @endif
                            class="formulario__campo  @error('ganancia-fraccion') borde__error @enderror">
                        @error('ganancia-fraccion')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fin contenedor Flex --}}

                <div class="formulario__campo-contenedor">
                    <div class="producto-formulario__venta">
                        <p class="producto-formulario__venta-precio" id="precio-fraccionado">$ ?</p>
                        <a id="btn-fraccionado" class="producto-formulario__venta-boton">
                            <i class="fa-solid fa-rotate-right"></i>
                        </a>
                    </div>
                </div>

            </div> {{-- Fin contenedor oculto --}}
            <input type="submit" value="Guardar Cambios" class="formulario__boton">
        </form>

        <button id="producto-destroy" class="formulario__boton formulario__boton--rojo">Eliminar Producto @if ($producto_fraccionado) Fraccionado @endif</button>

    </div>
@endsection
