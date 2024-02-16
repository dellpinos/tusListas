@extends('layouts.app')


@section('contenido')
    <div class="home__contenedor-principal">

        <h2 class="home__heading">
            Recuperar Password
        </h2>

        @if (session('status'))
            <p class="mensaje__info mensaje__warning">{{ session('status') }}</p>
        @endif

        <div class="formulario__contenedor width-full">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                @if (session('mensaje'))
                    <p class=" alerta__error">{{ session('mensaje') }}</p>
                @endif

                <div class="formulario__campo-contenedor">
                    <label for="email" class="formulario__label">Email</label>
                    <input type="email" id="email" name="email" placeholder="Tu email"
                        class="formulario__campo @error('email') borde__error @enderror" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <input type="submit" value="Enviar correo" class="formulario__boton" />
            </form>
        </div>

        <div class="formulario__opciones-contenedor mb-10">
            <a href="{{ route('login') }}" class="formulario__opciones-enlace">Iniciar Sesión</a>
        </div>

    </div>
@endsection
