@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Administrador
@endsection

@section('contenido')
    <div class="formulario__contenedor-boton">
        <a href="{{ route('buscador') }}" class="categoria__boton">&laquo; Volver</a>
        <a href="#" class="categoria__boton"> Estadísticas <i class="owner__icono fa-solid fa-chart-pie"></i></a>
    </div>

    <div class="owner__contenedor-sm">
        <div class="owner__flex">
            <label for="email-user" class="formulario__label">Email nuevo Usuario</label>
            <div class="formulario__campo-contenedor owner__campo-contenedor">
                <div class="width-full">
                <input type="email" id="email-user" placeholder="ejemplo@correo.com" name="email"
                    class="formulario__campo owner__campo">
                </div>

                <button class="formulario__boton owner__boton" id="btn-new-user">Enviar Invitación</button>
            </div>
        </div>

        <div class="owner__flex">
            <label for="name-empresa" class="formulario__label">Nombre de la Empresa</label>
            <div class="formulario__campo-contenedor owner__campo-contenedor">
                <div class="width-full">
                <input type="text" id="name-empresa" placeholder="Nombre de la empresa" name="name"
                    class="formulario__campo owner__campo">
                </div>
                <button class="formulario__boton owner__boton" id="btn-name-empresa">Guardar Cambios</button>
            </div>
        </div>

        <h3 class="owner__heading-tabla">Usuarios</h2>


            <table class="table" id="owner-table">


                {{-- Js --}}

            </table>



    </div>
@endsection()
