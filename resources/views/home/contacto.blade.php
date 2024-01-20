@extends('layouts.app')

@section('contenido')

<div class="ayuda__contenedor-xl">
    <h2>Contacto</h2>

    <div class="ayuda__grid">

        <div class="ayuda__contenedor">
            <h3>Guia y FAQ</h3>
            <a class="enlace" href="{{ route('documentacion') }}">
                <p>Ver información de TusListas</p>
            </a>
        </div>
        <div class="ayuda__contenedor">
            <h3>Soporte y Feedback</h3>
            <p><span>Foro: </span> <a class="enlace ayuda__enlace" target="_blank"
                    href="https://github.com/dellpinos/tusListas/discussions">TusListas</a></p>
            <p><span>Contacto: </span>tuslistas.app@gmail.com</p>
        </div>
    </div>
</div>
@endsection