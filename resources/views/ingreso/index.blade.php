@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Ingreso de Mercaderia
@endsection

@section('contenido')
<p class="mensaje__info">Dolar aplicado a esta mercaderia: U$S <span class="ingreso__numero">{{intval($dolar)}}</span></p>
    <div class="ingreso__contenedor">

        <h3>Ingreso de Productos</h3>

        <div class="ingreso__grid" id="mercaderia-grid">
            
            <label class="formulario__label ingreso__label" for="mercaderia-checkbox">Pendiente</label>
            <label class="formulario__label ingreso__label" for="mercaderia-checkbox">Cant</label>
            <label class="formulario__label ingreso__label" for="mercaderia-codigo">Código</label>
            <label class="formulario__label ingreso__label" for="mercaderia-nombre">Nombre</label>
            <label class="formulario__label ingreso__label" for="mercaderia-precio">Precio</label>
            <label class="formulario__label ingreso__label" for="mercaderia-descuento">% Descuento</label>
            <label class="formulario__label ingreso__label" for="mercaderia-semanas">Semanas</label>
            <label class="formulario__label ingreso__label" for="mercaderia-guardar"></label>
            <label class="formulario__label ingreso__label" for="mercaderia-guardar"></label>

            {{-- Js --}}

        </div>
    </div>
@endsection

