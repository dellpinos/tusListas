@extends('layouts.app')

@section('contenido')

    <div class="home__contenedor-principal">

        <h2 class="home__heading">
            @if (!$invitation)
                Nueva Empresa
            @else
                Nuevo Usuario
            @endif
        </h2>
        @if ($mensaje)
            {{-- Imprimo en pantalla el mensaje de error. --}}
            <div class="formulario__contenedor-md">
                <p class="mensaje__info">{{ $mensaje }}</p>
            </div>
        @else
            {{-- Muestro el formulario --}}

            <div class="formulario__contenedor-md">

                <div class="formulario__contenedor">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        {{-- Formulario empresa --}}

                        @if (!$invitation)
                            <div class="formulario__campo-contenedor">
                                <label for="empresa" class="formulario__label">Nombre de la Empresa</label>
                                <input type="text" id="name" name="name" placeholder="Nombre de tu empresa"
                                    class="formulario__campo @error('name') borde__error @enderror"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <p class=" alerta__error">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" value="{{ $invitation->token }}" name="token">
                        @endif

                        {{-- Formulario Owner --}}
                        <div class="formulario__campo-contenedor">
                            <label for="name" class="formulario__label">
                                @if (!$invitation)
                                    Nombre del Propietario
                                @else
                                    Nombre del Usuario
                                @endif

                            </label>
                            <input type="text" id="usuario" name="usuario" placeholder="Tu nombre"
                                class="formulario__campo @error('usuario') borde__error @enderror"
                                value="{{ old('usuario') }}">
                            @error('usuario')
                                <p class=" alerta__error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="formulario__campo-contenedor">
                            <label for="username" class="formulario__label">UserName</label>
                            <input type="text" id="username" name="username" placeholder="Tu nombre de usuario"
                                class="formulario__campo @error('username') borde__error @enderror"
                                value="{{ old('username') }}">
                            @error('username')
                                <p class=" alerta__error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="formulario__campo-contenedor">
                            <label for="email" class="formulario__label">Email</label>
                            <input type="email" id="email" name="email" placeholder="Tu casilla de email"
                                class="formulario__campo @error('email') borde__error @enderror"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class=" alerta__error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="formulario__campo-contenedor">
                            <label for="password" class="formulario__label">Password</label>
                            <input type="password" id="password" name="password" placeholder="Tu password"
                                class="formulario__campo @error('password') borde__error @enderror">
                            @error('password')
                                <p class=" alerta__error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="formulario__campo-contenedor">
                            <label for="password_confirmation" class="formulario__label">Repetir
                                Passsword</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Repite tu password" class="formulario__campo">
                        </div>

                        <input type="submit" value="Crear Cuenta" class="formulario__boton">
                    </form>
                </div>
            </div>

            <div class="formulario__opciones-contenedor">
                <a href="{{ route('login') }}" class="formulario__opciones-enlace">Ya tienes una cuenta?</a>
                <a href="{{ route('password.request') }}" class="formulario__opciones-enlace">He olvidado mi contraseña</a>
            </div>
        @endif

    </div>
@endsection
