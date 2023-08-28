@extends('layouts.dashboard')
@section('titulo')
    {{ $producto->nombre }}
@endsection

@push('scripts')
    @vite('resources/js/productoForm.js')
@endpush


@section('contenido')
    <div class=" contenedor-md">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de usuarios">
        </div>

        <div class="formulario__contenedor">
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
                    <label for="proveedor" class="formulario__label">Distribuidora</label>
                    <select class="formulario__campo @error('proveedor') border-red-500 @enderror" id="proveedor"
                        name="provider_id">

                        <option value="{{ $provider->id }}" selected >{{ $provider->nombre }}</option>

                        @foreach ($proveedores as $elemento)
                            <option value="{{ $elemento->id }}">{{ $elemento->nombre }}</option>
                        @endforeach

                        @error('proveedor')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="precio" class="formulario__label">Precio Costo sin IVA</label>
                    <input type="number" id="precio" name="precio" placeholder="Precio de costo en pesos"
                        value="{{ $precio->precio }}" class="formulario__campo @error('precio') border-red-500 @enderror">
                    @error('precio')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="dolar" class="formulario__label">Cotización dolar Blue
                        (compra)</label>
                    <input type="number" id="dolar" name="dolar" placeholder="Cotización al dia de la fecha"
                        value="{{ $precio->dolar }}" class="formulario__campo @error('dolar') border-red-500 @enderror">
                    @error('dolar')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>



                <label for="ganancia" class="formulario__label">Ganancia</label>
                <div class="formulario__contenedor-radio" id="contenedor-radios">

                    <label for="ganancia-categoria" class="formulario__label--small">Categoria</label>
                    <input type="radio" value="categoria" name="ganancia" class="cursor-pointer" id="ganancia-categoria"
                        {{ $producto->ganancia_tipo === 'categoria' ? 'checked' : '' }} />

                    <label for="ganancia-proveedor" class="formulario__label--small">Proveedor</label>
                    <input type="radio" value="proveedor" name="ganancia" class="cursor-pointer" id="ganancia-proveedor"
                        {{ $producto->ganancia_tipo === 'proveedor' ? 'checked' : '' }} />

                    <label for="ganancia-personalizada" class="formulario__label--small">Personalizada</label>
                    <input type="radio" value="" name="ganancia" class="cursor-pointer" id="ganancia-personalizada"
                        {{ $producto->ganancia_tipo === 'producto' ? 'checked' : '' }} />
                </div>
                <div class="formulario__campo-contenedor">
                    <input type="number" step="0.1" min="0" id="ganancia" name="ganancia"
                        placeholder="1.2, 1.7, 1.9" disabled value="{{ $producto->ganancia }}"
                        class=" formulario__campo formulario__campo--no-activo @error('ganancia') border-red-500 @enderror">
                    @error('ganancia')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>



                <input type="submit" value="Actualizar Producto" class="formulario__boton">
            </form>
        </div>

    </div>
@endsection
