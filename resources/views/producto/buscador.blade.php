@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Buscador
@endsection

@push('scripts')
    @vite('resources/js/buscadorProd.js')
@endpush


@section('contenido')
    <div class="buscador__grid">
        {{-- Aqui debe haber un formulario que envie el request para la consulta, luego hacer un buscador con Js --}}
        <div class=" buscador__contenedor">
            <form method="POST" action="#">
                @csrf
                <div class="buscador__campo-contenedor">
                    <label for="producto-codigo" class="formulario__label">Código del producto</label>
                    <input type="text" id="producto-codigo" name="producto-codigo" placeholder="C4GT, F320, 44G2, etc"
                        class="buscador__campo">
                </div>
                <div class="buscador__boton-contenedor">
                    <button class="buscador__boton" type="submit">Buscar por Código</button>
                </div>
            </form>
        </div>
        <div class=" buscador__contenedor">

            <div class="buscador__campo-contenedor">
                <label for="producto-nombre" class="formulario__label">Nombre del producto</label>
                <div class="relative">

                    <input type="text" id="producto-nombre-falso"
                        placeholder="Pipeta power, Pecera 60x20, Collar Cuero, etc" class="buscador__campo">
                    <div id="contenedor-buscador-opciones" class="buscador__opciones-contenedor display-none">
                        <input type="text" id="producto-nombre" name="producto-nombre" class="buscador__campo buscador__campo-focus" placeholder="Pipeta power, Pecera 60x20, Collar Cuero, etc">
                        <ul id="buscador-opciones">
                            <li>Opt</li>
                            <li>Opt</li>
                            <li>Opt</li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="buscador__boton-contenedor">
                <button class="buscador__boton">Buscar por Nombre</button>
            </div>



        </div>

    </div>
@endsection
