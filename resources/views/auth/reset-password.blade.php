@extends('layouts.app')

@section('contenido')
    <div class="home__contenedor-principal">

        <h2 class="home__heading">
            Cambiar Password
        </h2>

        <div class="formulario__contenedor width-full">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="formulario__campo-contenedor">
                    <label for="email" class="formulario__label">Email</label>
                    <input type="email" id="email" name="email" placeholder="Confirma tu email"
                        class="formulario__campo @error('email') borde__error @enderror" value="{{ old('email') }}"
                        required>
                    @error('email')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="password" class="formulario__label">Passsword</label>
                    <input type="password" id="password" name="password" placeholder="Nuevo password"
                        class="formulario__campo @error('password') borde__error @enderror">
                    @error('password')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="formulario__campo-contenedor">
                    <label for="password_confirmation" class="formulario__label">Repetir
                        Passsword</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Repite el nuevo password"
                        class="formulario__campo @error('password_confirmation') borde__error @enderror">

                    @error('password_confirmation')
                        <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="token" value="{{ request()->route('token') }}">
                <input type="submit" value="Cambiar Password" class="formulario__boton" />
            </form>
        </div>

    </div>
@endsection()
