@extends('layouts.dashboard')
@section('titulo')
    {{ $fabricante->nombre }}
@endsection


@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('fabricantes') }}" class="fabricante__boton">&laquo; Volver</a>
    </div>

    <div class="fabricante-formulario__grid">
        <form action="{{ route('fabricante.update') }}" method="POST">
            @csrf

            <input type="hidden" name="id" value="{{ $fabricante->id }}" />
            <div class="fabricante-formulario__contenedor-sm">
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del Fabricante</label>
                    <input required type="text" id="name" name="nombre"
                        placeholder="Holliday, Brower, Importado, etc"
                        class="formulario__campo @error('nombre') borde__error @enderror" value="{{ $fabricante->nombre }}">
                    @error('nombre')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="telefono" class="formulario__label">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Teléfono de contacto"
                        class="formulario__campo @error('telefono') borde__error @enderror"
                        value="{{ $fabricante->telefono }}">
                    @error('telefono')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="formulario__campo-contenedor">
                    <label for="vendedor" class="formulario__label">Nombre del vendendor</label>
                    <input type="text" id="vendedor" name="vendedor" placeholder="Nombre del vendedor"
                        class="formulario__campo @error('vendedor') borde__error @enderror"
                        value="{{ $fabricante->vendedor }}">
                    @error('vendedor')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="descripcion" class="formulario__label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Notas o descripción"
                        class="formulario__campo @error('descripcion') borde__error @enderror">{{ $fabricante->descripcion }}</textarea>
                    @error('descripcion')
                        <p class="alerta__error">{{ $message }}</p>
                    @enderror
                </div>



            </div>
            <input type="submit" value="Guardar Cambios" class="formulario__boton">
        </form>

    </div>
@endsection
