@extends('layouts.dashboard')
@section('titulo')
    Nuevo Proveedor
@endsection




@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('providers') }}" class="provider__boton">&laquo; Volver</a>
    </div>

    <div class="provider-formulario__grid">
        <form action="{{ route('provider.store') }}" method="POST">
            @csrf

            <div class="provider-formulario__contenedor">

                <div class="provider-formulario__contenedor-sm">
                    <div class="formulario__campo-contenedor">
                        <label for="name" class="formulario__label">Nombre del Proveedor</label>
                        <input required type="text" id="name" name="nombre"
                            placeholder="Nombre del proveedor"
                            class="formulario__campo @error('nombre') borde__error @enderror" value="{{ old('nombre') }}">
                        @error('nombre')
                            <p class=" alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="email" class="formulario__label">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email de contacto"
                            class="formulario__campo @error('email') borde__error @enderror" value="{{ old('email') }}">
                        @error('email')
                            <p class="alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="telefono" class="formulario__label">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono"
                            placeholder="Teléfono de contacto"
                            class="formulario__campo @error('telefono') borde__error @enderror"
                            value="{{ old('telefono') }}">
                        @error('telefono')
                            <p class=" alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                <div class="provider-formulario__contenedor-sm">


                    <div class="formulario__campo-contenedor">
                        <label for="vendedor" class="formulario__label">Nombre del vendendor</label>
                        <input type="text" id="vendedor" name="vendedor" placeholder="Nombre del vendedor"
                            class="formulario__campo @error('vendedor') borde__error @enderror"
                            value="{{ old('vendedor') }}">
                        @error('vendedor')
                            <p class=" alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="web" class="formulario__label">Sitio Web</label>
                        <input type="text" id="web" name="web" placeholder="www.ejemplo.com.ar"
                            class="formulario__campo @error('web') borde__error @enderror" value="{{ old('web') }}">
                        @error('web')
                            <p class=" alerta__error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="formulario__campo-contenedor">
                        <label for="ganancia" class="formulario__label">Ganancia</label>
                        <input required type="number" step="0.01" min="0.01" max="9.99" id="ganancia"
                            name="ganancia" placeholder="1.5, 1.8, 1.9"
                            class="formulario__campo @error('ganancia') borde__error @enderror"
                            value="{{ old('ganancia') }}">
                        @error('ganancia')
                            <p class=" alerta__error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Falta la categoria, probablemente necesite un hidden submit porque es el id de la tabla pivote --}}


            </div>
            <input type="submit" value="Nuevo Proveedor" class="formulario__boton">

        </form>
    </div>
@endsection
