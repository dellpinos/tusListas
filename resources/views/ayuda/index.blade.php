@extends('layouts.dashboard')

@section('meta')
    {{-- Esta etiqueta me permite leer el token csrf desde Js --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('titulo')
    Ayuda
@endsection

@section('contenido')
    <div class="ayuda__grid">
        <div class="ayuda__contenedor">
            <h3>Tutorial Primeros Pasos</h3>
            <div class="ayuda__contenedor-flex">
                <p>Estado: </p>
                <button class="boton ayuda__boton ayuda__boton--activo" id="ayuda-boton-tutorial">Activado</button>
            </div>
        </div>
        <div class="ayuda__contenedor">
            <h3>Guia y FAQ</h3>
            <a class="enlace" href="{{ route('documentacion') }}">
                <p>Ver información de TusListas</p>
            </a>
        </div>
        <div class="ayuda__contenedor">
            <h3>Soporte y Feedback</h3>
            <p><span>Foro: </span> <a class="enlace ayuda__enlace" target="_blank" href="https://github.com/dellpinos/tusListas/discussions">TusListas</a></p>
            <p><span>Contacto: </span>tuslistas.app@gmail.com</p>
        </div>
    </div>
@endsection()