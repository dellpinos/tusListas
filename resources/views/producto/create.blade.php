@extends('layouts.dashboard')
@section('titulo')
    Nuevo Producto
@endsection

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('contenido')
    <div id="contenedor-pendientes">
        {{-- Js --}}
    </div>

    <form action="{{ route('producto.store') }}" method="POST" class="producto-formulario__grid">
        @csrf
        <div class="formulario__campo-contenedor">
            <label for="codigo" class="formulario__label">Código del producto</label>
            <input type="text" id="codigo" name="codigo" readonly
                class="formulario__campo formulario__campo--codigo @error('codigo') borde__error @enderror"
                value="{{ strtoupper($codigo) }}">
            @error('codigo')
                <p class="alerta__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="producto-formulario__contenedor">
            <div class="producto-formulario__contenedor-sm">
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del producto *</label>
                    <input required type="text" id="name" name="nombre" placeholder="Nombre del producto"
                        maxlength="60" class="formulario__campo @error('nombre') borde__error @enderror"
                        value="{{ old('nombre') }}">
                    @error('nombre')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="categoria" class="formulario__label">Categoria *</label>
                    <select required class="formulario__campo @error('categoria') borde__error @enderror" id="categoria"
                        name="categoria_id">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ intval(old('categoria_id')) === $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}</option>
                        @endforeach

                        @error('categoria')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="provider" class="formulario__label">Proveedor *</label>
                    <select required class="formulario__campo @error('provider') borde__error @enderror" id="provider"
                        name="provider_id">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}"
                                {{ intval(old('provider_id')) === $provider->id ? 'selected' : '' }}>
                                {{ $provider->nombre }}</option>
                        @endforeach

                        @error('provider')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="fabricante" class="formulario__label">Fabricante *</label>
                    <select required class="formulario__campo @error('fabricante') borde__error @enderror" id="fabricante"
                        name="fabricante_id">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($fabricantes as $fabricante)
                            <option value="{{ $fabricante->id }}"
                                {{ intval(old('fabricante_id')) === $fabricante->id ? 'selected' : '' }}>
                                {{ $fabricante->nombre }}</option>
                        @endforeach

                        @error('fabricante')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="dolar" class="formulario__label">Cotización dolar Blue
                        (compra) *</label>
                    <input required type="number" id="dolar" name="dolar" placeholder="0" min="1"
                        class="formulario__campo  @error('dolar') borde__error @enderror" value="{{ $dolar_pred }}">
                    @error('dolar')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                    <a class="enlace__normal" href="https://dolarhoy.com/cotizaciondolarblue" target="_blank">Consultar
                        Dolar Hoy</a>
                </div>

                <div class="producto-formulario__flex producto-formulario__contenedor-checkbox">
                    <input type="checkbox" id="check-fraccion" />
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
                    ">Venta fraccionado</label>

                </div>
            </div>
            <div class="producto-formulario__contenedor-sm">

                <div class="formulario__campo-contenedor">
                    <label for="precio" class="formulario__label">Precio Costo sin IVA *</label>
                    <input required type="number" step="any" id="precio" name="precio" placeholder="0"
                        class="formulario__campo  @error('precio') borde__error @enderror" value="{{ old('precio') }}">
                    @error('precio')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="precio-iva" class="formulario__label">Precio Costo con IVA *</label>
                    <input type="number" step="any" id="precio-iva" placeholder="0"
                        class="formulario__campo  @error('precio') borde__error @enderror">
                    @error('precio')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <label for="ganancia" class="formulario__label">Ganancia *</label>
                <div class="formulario__contenedor-radio" id="contenedor-radios">
                    <label for="ganancia-categoria" class="formulario__label--small">Categoria</label>
                    <input type="radio" value="categoria" name="ganancia" class="cursor-pointer"
                        id="ganancia-categoria" />
                    <label for="ganancia-provider" class="formulario__label--small">Proveedor</label>
                    <input type="radio" value="provider" name="ganancia" class="cursor-pointer"
                        id="ganancia-provider" checked />
                    <label for="ganancia-personalizada" class="formulario__label--small">Personalizada</label>
                    <input type="radio" value="personalizada" name="ganancia" class="cursor-pointer"
                        id="ganancia-personalizada" />
                </div>
                <div class="formulario__campo-contenedor">
                    <input type="number" step="0.01" min="1" max="19.9" id="ganancia"
                        {{-- elimino el name --}} placeholder="1.2, 1.7, 1.9" readonly
                        class=" formulario__campo formulario__campo--no-activo  @error('ganancia') borde__error @enderror">
                    @error('ganancia')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" id="ganancia-numero" name="ganancia_numero" value="">

                {{-- Si el producto era un "pendiente": descuento, duracion y stock --}}
                <input type="hidden" name="desc_porc" value="">
                <input type="hidden" name="desc_duracion" value="">
                <input type="hidden" name="stock" value="">

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

        <div id="producto-contenedor-oculto" class="producto-formulario__contenedor-oculto"> {{-- Contenedor Fraccionado --}}

            <div class="formulario__campo-contenedor">
                <label for="codigo-fraccionado" class="formulario__label">Código del Producto Fraccionado</label>
                <input type="text" id="codigo-fraccionado" name="codigo_fraccionado" readonly
                    class="formulario__campo formulario__campo--codigo @error('codigo') borde__error @enderror"
                    value="">
                @error('codigo-fraccionado')
                    <p class="alerta__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="producto-formulario__flex">

                <div class="formulario__campo-contenedor width-full">
                    <label for="unidad-fraccion" class="formulario__label">Unidad del Producto *</label>
                    <input type="text" id="unidad-fraccion" name="unidad_fraccion"
                        placeholder="blister, frasco, kg, unidad, etc" maxlength="30"
                        class="formulario__campo @error('unidad_fraccion') borde__error @enderror"
                        value="{{ old('unidad_fraccion') }}">
                    @error('unidad_fraccion')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor width-full">
                    <label for="contenido-total" class="formulario__label">Total de Unidades *</label>
                    <input type="number" id="contenido-total" name="contenido_total" placeholder="25, 3, 500"
                        min="1" max="9999"
                        class="formulario__campo  @error('contenido_total') borde__error @enderror"
                        value="{{ old('contenido_total') }}">
                    @error('contenido_total')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor width-full">
                    <label for="ganancia-fraccion" class="formulario__label">Ganancia Extra Fracción *</label>
                    <input type="number" step="0.01" min="1" max="19.9" id="ganancia-fraccion"
                        name="ganancia_fraccion" placeholder="1.1, 1.2, 1.4"
                        class="formulario__campo  @error('ganancia_fraccion') borde__error @enderror"
                        value="{{ old('ganancia_fraccion') }}">
                    @error('ganancia_fraccion')
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

        <input type="submit" value="Crear Producto" class="formulario__boton">

    </form>
@endsection
