@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Nueva Categoria
@endsection

@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('categorias') }}" class="categoria__boton">&laquo; Volver</a>
    </div>

    <div class="categoria-formulario__grid">
        <form action="{{ route('categoria.store') }}" method="POST">
            @csrf

            <div class="categoria-formulario__contenedor-sm">
                <div class="formulario__campo-contenedor">
                    <label for="nombre" class="formulario__label">Nombre de la categoria *</label>
                    <input required type="text" id="name" name="nombre"
                        placeholder="Nombre de la categoria" maxlength="60"
                        class="formulario__campo @error('nombre') borde__error @enderror" value="{{ old('nombre') }}">
                    @error('nombre')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="ganancia" class="formulario__label">Ganancia *</label>
                    <input required type="number" step="0.01" min="1" max="19.99" id="ganancia"
                        name="ganancia" placeholder="1.5, 1.8, 1.9"
                        class="formulario__campo @error('ganancia') borde__error  @enderror" value="{{ old('ganancia') }}">
                    @error('ganancia')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <input type="submit" value="Crear Categoria" class="formulario__boton">
        </form>

    </div>
@endsection
