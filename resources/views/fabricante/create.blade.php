@extends('layouts.dashboard')
@section('titulo')
    Nuevo Fabricante
@endsection




@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('fabricantes') }}" class="fabricante__boton">&laquo; Volver</a>
    </div>

    <div class="fabricante-formulario__grid">
        <form action="{{ route('fabricante.store') }}" method="POST">
            @csrf

            <div class="fabricante-formulario__contenedor-sm">
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del Fabricante</label>
                    <input required type="text" id="name" name="nombre"
                        placeholder="Nombre del fabricante"
                        class="formulario__campo @error('nombre') borde__error @enderror" value="{{ old('nombre') }}">
                    @error('nombre')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="telefono" class="formulario__label">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Teléfono de contacto"
                        class="formulario__campo @error('telefono') borde__error @enderror" value="{{ old('telefono') }}">
                    @error('telefono')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="vendedor" class="formulario__label">Nombre del vendendor</label>
                    <input type="text" id="vendedor" name="vendedor" placeholder="Nombre del vendedor"
                        class="formulario__campo @error('vendedor') borde__error @enderror" value="{{ old('vendedor') }}">
                    @error('vendedor')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="descripcion" class="formulario__label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Notas o descripción"
                        class="formulario__campo @error('descripcion') borde__error @enderror"></textarea>
                    @error('descripcion')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>



            </div>
            <input type="submit" value="Nuevo Fabricante" class="formulario__boton">
        </form>

    </div>
@endsection
