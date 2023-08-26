@extends('layouts.dashboard')
@section('titulo')
    Nuevo Laboratorio - Fabricante
@endsection


@section('contenido')
    <div class="provider__contenedor-boton">
        <a href="{{ route('fabricantes') }}" class="fabricante__boton">&laquo; Volver</a>
        <a href="#" class="fabricante__boton">Buscar Proveedor</a>
    </div>

    <div class="contenedor-md">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de fabricante">
        </div>

        <div class="formulario__contenedor">
            <form action="{{ route('fabricante.store') }}" method="POST">
                @csrf
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del Laboratiorio -
                        Fabricante</label>
                    <input type="text" id="name" name="name" placeholder="Holliday, Brower, Importado, etc"
                        class="formulario__campo @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="telefono" class="formulario__label">Telefono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Telefono del Laboratorio o Fabricante"
                        class="formulario__campo @error('telefono') border-red-500 @enderror"
                        value="{{ old('telefono') }}">
                    @error('telefono')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="vendedor" class="formulario__label">Nombre del vendendor</label>
                    <input type="text" id="vendedor" name="vendedor" placeholder="Nombre del vendedor"
                        class="formulario__campo @error('vendedor') border-red-500 @enderror"
                        value="{{ old('vendedor') }}">
                    @error('vendedor')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="descripcion" class="formulario__label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Notas o descripción"
                        class="formulario__campo @error('descripcion') border-red-500 @enderror"></textarea>
                    @error('descripcion')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>



                <input type="submit" value="Nuevo Fabricante"
                class="formulario__boton">
            </form>
        </div>

    </div>
@endsection
