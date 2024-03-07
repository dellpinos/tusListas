@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Revisión de Stock
@endsection

@section('contenido')

<p class="mensaje__info">Aquí puedes consultar/modificar el stock y los descuentos</p>
    <div class="ingreso__contenedor">

        <h3>Productos</h3>

        <div class="ingreso__grid" id="owner-stock-grid"> 
            
            <label class="formulario__label ingreso__label" for="mercaderia-checkbox">Pendiente</label>
            <label class="formulario__label ingreso__label" for="mercaderia-checkbox">Cant</label>
            <label class="formulario__label ingreso__label" for="mercaderia-codigo">Código</label>
            <label class="formulario__label ingreso__label" for="mercaderia-nombre">Nombre</label>
            <label class="formulario__label ingreso__label" for="mercaderia-precio">Precio</label>
            <label class="formulario__label ingreso__label" for="mercaderia-descuento">% Descuento</label>
            <label class="formulario__label ingreso__label" for="mercaderia-semanas">Semanas</label>
            <label class="formulario__label ingreso__label" for="mercaderia-guardar"></label>

            {{-- Js --}}

        </div>
    </div>
@endsection

