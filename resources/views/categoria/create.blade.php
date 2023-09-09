@extends('layouts.dashboard')
@section('titulo')
    Nueva Categoria
@endsection


@section('contenido')
    <div class="categoria__contenedor-boton">
        <a href="{{ route('categorias') }}" class="provider__boton">&laquo; Volver</a>
        <a href="#" class="provider__boton">Buscar Categoria</a>
    </div>

    <div class="categoria-formulario__grid">
        <form action="{{ route('categoria.store') }}" method="POST">
            @csrf

            <div class="categoria-formulario__contenedor-sm">
                <div class="formulario__campo-contenedor">
                    <label for="nombre" class="formulario__label">Nombre de la categoria</label>
                    <input required type="text" id="name" name="nombre" placeholder="Acuario, Farmacos, Pet Shop, etc"
                        class="formulario__campo @error('nombre') borde__error @enderror" value="{{ old('nombre') }}">
                    @error('nombre')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="ganancia" class="formulario__label">Ganancia</label>
                    <input required type="number" step="0.01" min="0.01" max="9.99" id="ganancia" name="ganancia" placeholder="1.5, 1.8, 1.9"
                        class="formulario__campo @error('ganancia') borde__error  @enderror" value="{{ old('ganancia') }}">
                    @error('ganancia')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                {{--  Falta el proveedor, probablemente necesite un hidden submit porque es el id de la tabla pivote --}}
            </div>
            <input type="submit" value="Crear Categoria" class="formulario__boton">
        </form>

    </div>
@endsection
