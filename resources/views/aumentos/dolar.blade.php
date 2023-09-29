@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('titulo')
    Aumento en Base al U$S
@endsection

@section('contenido')
    <div class="aumento-formulario__contenedor">

        <h3>Dolar al dia de la fecha</h3>

        <div class="aumento-formulario__campo-flex">
            <label for="aumento-dolar" class="formulario__label">U$S</label>

            <input type="number" id="aumento-dolar" placeholder="Dolar Hoy" class="formulario__campo">
        </div>

        <a class="formulario__boton" id="btn-dolar">Buscar Desactualizados</a>
    </div>
    <div class="aumento-formulario__contenedor-actualizar">
        <p class="mensaje__info aumento-formulario__mensaje--act" id="desactualizados-info"></p>

        <a class="formulario__boton aumento-formulario__boton--act display-none" id="btn-dolar-actualizar">Actualizar Todos</a>
    </div>

    <div class="mensaje__contenedor" id="desactualizados-mensaje"></div>

    
    <table class="table" id=""> {{-- <<< --}}
        <thead class="table__thead">
            <tr>
                <th scope="col" class="table__th">Dolar</th>
                <th scope="col" class="table__th">Código</th>
                <th scope="col" class="table__th">Nombre</th>
                <th scope="col" class="table__th">Precio Costo</th>
                <th scope="col" class="table__th">Precio Venta</th>
                <th scope="col" class="table__th">Fecha</th>
                <th scope="col" class="table__th">Enlace</th>
            </tr>
        </thead>
        <tbody class="table__tbody" id="aumento-dolar-registros"> <!-- Tabla Body -->


            {{--  --}}

        </tbody> <!-- Fin Tabla Body -->

    </table>

    <div id="table-paginacion"></div>

@endsection
