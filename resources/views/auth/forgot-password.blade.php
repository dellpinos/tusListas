@extends('layouts.app')

@section('titulo')
    Debes confirmar tu email
@endsection

@section('contenido')

<div class="formulario__contenedor-md">

    

    <div class="formulario__contenedor">
        <form method="POST" action="{{ route('forgot-password') }}">
            @csrf
            @if (session('mensaje'))
                <p class=" alerta__error">{{ session('mensaje') }}</p>
            @endif

            <div class="formulario__campo-contenedor">
                <label for="email" class="formulario__label">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu email"
                    class="formulario__campo @error('email') borde__error @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <p class=" alerta__error">{{ $message }}</p>
                @enderror
            </div>

            <input type="submit" value="Iniciar Sesión" class="formulario__boton"/>
        </form>
    </div>

</div>

<div class="formulario__opciones-contenedor mb-10">
    <a href="{{ route('login') }}" class="formulario__opciones-enlace">Ya tienes una cuenta?</a>
</div>

@endsection

