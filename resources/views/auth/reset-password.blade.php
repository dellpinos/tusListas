@extends('layouts.app')

@section('titulo')
    Nuevo Password
@endsection

@section('contenido')


    
    <div class="formulario__contenedor width-full">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

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
                    placeholder="Repite tu password" class="formulario__campo @error('password_confirmation') borde__error @enderror">

                    @error('password_confirmation')
                <p class=" alerta__error">{{ $message }}</p>
            @enderror
                </div>
                
                <input type="submit" value="Cambiar Password" class="formulario__boton"/>
            </div>


        </form>
    </div>




@endsection()