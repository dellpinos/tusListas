@extends('layouts.app')

@section('titulo')
    Nueva Empresa
@endsection

@section('contenido')
    <div class="formulario__contenedor-md">

        
        
        <div class="formulario__contenedor">
            <form action="{{ route('register') }}" method="POST">   {{-- Modificar --}}
                @csrf
                {{-- Formulario empresa --}}
                
                <div class="formulario__campo-contenedor">
                    <label for="empresa" class="formulario__label">Nombre de la Empresa</label>
                    <input type="text" id="name" name="name" placeholder="Nombre de tu empresa"
                    class="formulario__campo @error('name') borde__error @enderror"
                    value="{{ old('name') }}">
                    @error('name')
                    <p class=" alerta__error">{{ $message }}</p>
                    @enderror
                </div>
                

                {{-- Formulario Owner --}}
                <div class="formulario__campo-contenedor">
                    <label for="name" class="formulario__label">Nombre del Propietario</label>
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
                    <label for="password" class="formulario__label">Passsword</label>
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

                <input type="submit" value="Crear Cuenta"
                    class="formulario__boton">
            </form>
        </div>

    </div>
@endsection
