@extends('layouts.dashboard')
@section('titulo')
    {{ $categoria->nombre}}
@endsection


@section('contenido')
    <div class="categoria__contenedor-boton">
        <a href="{{ route('categorias') }}" class="provider__boton">&laquo; Volver</a>
    </div>

    <div class="categoria-formulario__grid">
        <form action="{{ route('categoria.update') }}" method="POST">
            @csrf

            <input type="hidden" name="id" value="{{ $categoria->id }}" />

            <div class="categoria-formulario__contenedor-sm">
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre de la categoria</label>
                    <input type="text" id="name" name="name" placeholder="Acuario, Farmacos, Pet Shop, etc"
                        class="formulario__campo @error('name') border-red-500 @enderror" value="{{ $categoria->nombre }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="ganancia" class="formulario__label">Ganancia</label>
                    <input type="text" id="ganancia" name="ganancia" placeholder="1.5, 1.8, 1.9"
                        class="formulario__campo @error('ganancia') border-red-500 @enderror" value="{{ $categoria->ganancia }}">
                    @error('ganancia')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                {{--  Falta el proveedor, probablemente necesite un hidden submit porque es el id de la tabla pivote --}}
            </div>
            <input type="submit" value="Guardar Cambios" class="formulario__boton">
        </form>

    </div>
@endsection
