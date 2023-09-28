@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Aumentos Generales
@endsection

@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('aumento.dolar') }}" class="categoria__boton">Aumento Dolar</a>
        <a href="{{ route('aumento.listado') }}" class="categoria__boton">Registro de Aumentos</a>
    </div>
    <div class="aumento-formulario__contenedor-sm">
        <div class="aumento-formulario__flex">
            <div class="aumento-formulario__contenedor-xs ">
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-categoria" class="formulario__label">Categoria</label>
                    <select class="formulario__campo" id="aumentos-categoria">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-porc-cat" class="formulario__label">Porcentaje de Aumento %</label>
                    <input type="number" step="any" id="aumentos-porc-cat" placeholder="2, 10, 30"
                        class="formulario__campo">
                </div>
            </div>
            <button class="formulario__boton aumento-formulario__boton" id="btn-aumentos-cat">Aplicar Aumento</button>
        </div>
    </div>

    <div class="aumento-formulario__contenedor-sm">
        <div class="aumento-formulario__flex">
            <div class="aumento-formulario__contenedor-xs ">
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-provider" class="formulario__label">Proveedor</label>
                    <select class="formulario__campo" id="aumentos-provider">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($providers as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-porc-pro" class="formulario__label">Porcentaje de Aumento %</label>
                    <input type="number" step="any" id="aumentos-porc-pro" placeholder="2, 10, 30"
                        class="formulario__campo">
                </div>
            </div>
            <button class="formulario__boton aumento-formulario__boton" id="btn-aumentos-pro">Aplicar Aumento</button>
        </div>
    </div>

    <div class="aumento-formulario__contenedor-sm">
        <div class="aumento-formulario__flex">
            <div class="aumento-formulario__contenedor-xs ">
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-fabricantes" class="formulario__label">Fabricante</label>
                    <select class="formulario__campo" id="aumentos-fabricantes">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($fabricantes as $fabricante)
                            <option value="{{ $fabricante->id }}">{{ $fabricante->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="formulario__campo-contenedor aumento-formulario__campo-contenedor">
                    <label for="aumentos-porc-fab" class="formulario__label">Porcentaje de Aumento %</label>
                    <input type="number" step="any" id="aumentos-porc-fab" placeholder="2, 10, 30"
                        class="formulario__campo">
                </div>
            </div>
            <button class="formulario__boton aumento-formulario__boton" id="btn-aumentos-fab">Aplicar Aumento</button>
        </div>
    </div>

@endsection