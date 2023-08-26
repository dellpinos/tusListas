@extends('layouts.dashboard')
@section('titulo')
    Nuevo Proveedor
@endsection


@section('contenido')

<div class="provider__contenedor-boton" >
    <a href="{{ route('proveedores') }}" class="provider__boton">&laquo; Volver</a>
    <a href="#" class="provider__boton">Buscar Proveedor</a>
</div>
    <div class="contenedor-md">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de proveedor">
        </div>

        <div class="formulario__contenedor">
            <form action="{{ route('proveedor.store') }}" method="POST">
                @csrf
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del Proveedor</label>
                    <input type="text" id="name" name="name" placeholder="Arcuri, Lepore, Panacea, etc"
                        class="formulario__campo @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="email" class="formulario__label">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email de contacto"
                        class="formulario__campo @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}">
                    @error('email')
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
                    <label for="web" class="formulario__label">Sitio Web</label>
                    <input type="text" id="web" name="web" placeholder="www.arcuri.com.ar"
                        class="formulario__campo @error('web') border-red-500 @enderror"
                        value="{{ old('web') }}">
                    @error('web')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="ganancia" class="formulario__label">Ganancia</label>
                    <input type="text" id="ganancia" name="ganancia" placeholder="1.5, 1.8, 1.9"
                        class="formulario__campo @error('ganancia') border-red-500 @enderror"
                        value="{{ old('ganancia') }}">
                    @error('ganancia')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

{{--                 Falta la categoria, provablemente necesite un hidden submit porque es el id de la tabla pivote --}}


                <input type="submit" value="Nuevo Proveedor"
                    class="formulario__boton">
            </form>
        </div>

    </div>
@endsection
