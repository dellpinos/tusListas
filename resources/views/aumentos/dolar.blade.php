@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Aumento en Base al U$S


    <div class="aumento-formulario__contenedor">

    <h3>Dolar al dia de la fecha</h3>


        <div class="aumento-formulario__campo-flex">
            <label for="aumento-dolar" class="formulario__label">U$S</label>

            <input type="text" id="aumento-dolar" name="name" placeholder="Dolar Hoy" class="formulario__campo">
        </div>

        
        <a class="formulario__boton" id="btn-dolar" >Buscar Desactualizados</a >
</div>

    <div id="aumento-dolar-registros">
        <p>No hay registros</p>
    </div>
    <a class="formulario__boton" id="btn-dolar-actualizar" >Actualizar todos</a >
@endsection
