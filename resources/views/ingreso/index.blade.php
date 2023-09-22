@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Ingreso de Mercaderia
@endsection

@section('contenido')
<p class="mensaje__info">Dolar aplicado a esta mercaderia (el mas alto registrado): U$S {{intval($dolar)}}</p>
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
