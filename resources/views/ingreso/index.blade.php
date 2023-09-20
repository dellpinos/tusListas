@extends('layouts.dashboard')

@section('titulo')
    Ingreso de Mercaderia
@endsection

@section('contenido')

<div class="formulario__contenedor">

    <h3>Ingresar Prducto</h3>

    <div class="aumento-formulario__campo-flex">


        <input type="number" id="aumento-dolar" placeholder="Dolar" class="formulario__campo">
        <input type="number" id="aumento-dolar" placeholder="Dolar" class="formulario__campo">
        <input type="number" id="aumento-dolar" placeholder="Dolar" class="formulario__campo">
        <input type="number" id="aumento-dolar" placeholder="Dolar" class="formulario__campo">
        <input type="number" id="aumento-dolar" placeholder="Dolar" class="formulario__campo">
    </div>

    <a class="formulario__boton" id="btn-dolar">Esto es una mierda</a>
</div>

@endsection