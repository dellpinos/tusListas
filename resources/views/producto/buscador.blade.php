@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Buscador
@endsection




@section('contenido')

@if (session('mensaje'))

    <p class="mensaje__error">{{session('mensaje')}}</p>
    
@endif
    <div class="buscador__grid">
        {{-- Aqui debe haber un formulario que envie el request para la consulta, luego hacer un buscador con Js --}}
        <div class=" buscador__contenedor">

                <div class="buscador__campo-contenedor">
                    <label for="producto-codigo" class="formulario__label">Código del producto</label>
                    <input type="text" id="producto-codigo" name="producto-codigo" placeholder="C4GT, F320, 44G2, etc"
                        class="buscador__campo">
                </div>
                <div class="buscador__boton-contenedor">
                    <a class="buscador__boton" id="btn-codigo">Buscar por Código</a>
                </div>

        </div>
        <div class=" buscador__contenedor">

            <div class="buscador__campo-contenedor">
                <label for="producto-nombre" class="formulario__label">Nombre del producto</label>
                <div id="contenedor-input" class="relative">

                    <input type="text" id="producto-nombre-falso"
                        placeholder="Pipeta power, Pecera 60x20, Collar Cuero, etc" class="buscador__campo">
                </div>

            </div>
            <div class="buscador__boton-contenedor">
                <button class="buscador__boton" id="btn-nombre">Buscar por Nombre</button>
            </div>

        </div>

    </div>
    <div class=" producto__grid producto__card-contenedor" id="card-producto">
        {{-- Este contenido se genera con Js --}}
    </div>



@endsection

