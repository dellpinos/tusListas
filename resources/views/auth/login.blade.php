@extends('layouts.app')

@section('titulo')
    Iniciar Sesión
@endsection

{{-- Falta definir las clases de las alertas (pasarlo todo a SASS) y el resposive --}}

@section('contenido')

<div class="formulario__contenedor-md">

    <div class="formulario__contenedor">
        <form method="POST" action="{{ route('login') }}" novalidate>
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
            <div class="formulario__campo-contenedor">
                <label for="password" class="formulario__label">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu password" required
                    class="formulario__campo @error('password') borde__error @enderror">
                @error('password')
                    <p class=" alerta__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="formulario__campo-contenedor--checkbox">
                <input type="checkbox" name="remember"> 
                <label class="formulario__label">Mantener sesión abierta</label>
            </div>

            <input type="submit" value="Iniciar Sesión" class="formulario__boton"/>
        </form>
    </div>

</div>

@endsection()