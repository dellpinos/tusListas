@extends('layouts.dashboard')
@section('titulo')
    {{ $provider->name }}
@endsection


@section('contenido')
    <div class="provider__contenedor-boton">
        <a href="{{ route('proveedores') }}" class="provider__boton">&laquo; Volver</a>
    </div>

    <div class="proveedor-formulario__grid">
        <form action="{{ route('proveedor.update') }}" method="POST">
            @csrf

            <div class="proveedor-formulario__contenedor">

                <div class="proveedor-formulario__contenedor-sm">
                    <div class="formulario__campo-contenedor">
                        <label for="name" class="formulario__label">Nombre del Proveedor</label>
                        <input type="text" id="name" name="name" placeholder="Arcuri, Lepore, Panacea, etc"
                            class="formulario__campo @error('name') border-red-500 @enderror" value="{{ $provider->nombre }}">
                        @error('name')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="email" class="formulario__label">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email de contacto"
                            class="formulario__campo @error('email') border-red-500 @enderror" value="{{ $provider->email }}">
                        @error('email')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="telefono" class="formulario__label">Telefono</label>
                        <input type="tel" id="telefono" name="telefono"
                            placeholder="Telefono del Laboratorio o Fabricante"
                            class="formulario__campo @error('telefono') border-red-500 @enderror"
                            value="{{ $provider->telefono }}">
                        @error('telefono')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="proveedor-formulario__contenedor-sm">


                    <div class="formulario__campo-contenedor">
                        <label for="vendedor" class="formulario__label">Nombre del vendendor</label>
                        <input type="text" id="vendedor" name="vendedor" placeholder="Nombre del vendedor"
                            class="formulario__campo @error('vendedor') border-red-500 @enderror"
                            value="{{ $provider->vendedor }}">
                        @error('vendedor')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="web" class="formulario__label">Sitio Web</label>
                        <input type="text" id="web" name="web" placeholder="www.arcuri.com.ar"
                            class="formulario__campo @error('web') border-red-500 @enderror" value="{{ $provider->web }}">
                        @error('web')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="ganancia" class="formulario__label">Ganancia</label>
                        <input type="text" id="ganancia" name="ganancia" placeholder="1.5, 1.8, 1.9"
                            class="formulario__campo @error('ganancia') border-red-500 @enderror"
                            value="{{ $provider->ganancia }}">
                        @error('ganancia')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Falta la categoria, provablemente necesite un hidden submit porque es el id de la tabla pivote --}}


            </div>
            <input type="submit" value="Guardar Cambios" class="formulario__boton">

        </form>
    </div>
@endsection
