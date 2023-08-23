@extends('layouts.app')

@section('titulo')
    Iniciar Sesión
@endsection

{{-- Falta definir las clases de las alertas (pasarlo todo a SASS) y el resposive --}}

@section('contenido')

<div class="md:flex md:justify-center md:gap-10 md:items-center">
    <div class="md:w-6/12 p-5">
        <img src="#" alt="Imagen login de usuarios">
    </div>

    <div class="formulario__contenedor">
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            @if (session('mensaje'))
                <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ session('mensaje') }}</p>
            @endif

            <div class="formulario__campo-contenedor">
                <label for="email" class="formulario__label">Email</label>
                <input type="email" id="email" name="email" placeholder="Tu email"
                    class="formulario__campo @error('email') border-red-500 @enderror"
                    value="{{ old('email') }}">
                @error('email')
                    <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                @enderror
            </div>
            <div class="formulario__campo-contenedor">
                <label for="password" class="formulario__label">Password</label>
                <input type="password" id="password" name="password" placeholder="Tu password"
                    class="formulario__campo @error('password') border-red-500 @enderror">
                @error('password')
                    <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
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