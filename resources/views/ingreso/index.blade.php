@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Ingreso de Mercaderia
@endsection

@section('contenido')
    <div class="ingreso__contenedor">

        <h3>Productos</h3>

        <div class="ingreso__grid" id="mercaderia-grid">

            <label class="formulario__label text-center" for="mercaderia-checkbox">Pendiente</label>
            <label class="formulario__label text-center" for="mercaderia-checkbox">Cant</label>

            <label class="formulario__label text-center" for="mercaderia-codigo">Código</label>
            <label class="formulario__label text-center" for="mercaderia-nombre">Nombre</label>
            <label class="formulario__label text-center" for="mercaderia-precio">Costo sin IVA</label>
            <label class="formulario__label text-center" for="mercaderia-descuento">Descuento</label>
            <label class="formulario__label text-center" for="mercaderia-semanas">Duración</label>
            <label class="formulario__label text-center" for="mercaderia-guardar"></label>
            <label class="formulario__label text-center" for="mercaderia-guardar"></label>


            {{-- Js --}}



        </div>


    </div>
@endsection


{{-- <div class="formulario__contenedor-checkbox">
                    <input type="checkbox" class="formulario__checkbox" id="mercaderia-checkbox">
                </div>
                <input type="number" id="mercaderia-codigo" placeholder="Código" class="formulario__campo">
                <input type="text" id="mercaderia-nombre" placeholder="Nombre" class="formulario__campo">
                <input type="number" id="mercaderia-precio" placeholder="Precio sin IVA" class="formulario__campo">
                <input type="number" id="mercaderia-descuento" placeholder="% Descuento" class="formulario__campo">
                <select class="formulario__campo" id="mercaderia-semanas">
                    <option value="0" selected>0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                </select>

                <button class="boton" id="mercaderia-guardar">Guardar</button> --}}
