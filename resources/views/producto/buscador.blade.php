@extends('layouts.dashboard')

@section('titulo')
    Buscador
@endsection


@section('contenido')
    {{-- Aqui debe haber un formulario que envie el request para la consulta, luego hacer un buscador con Js --}}
    <div class=" bg-white p-6 rounded-lg shadow-xl">
        <form method="POST" action="#">
            @csrf

            <input type="text" id="producto" name="producto" placeholder="Que estas buscando?"
                class="border p-3 w-full rounded-lg">

            <button type="submit">Buscar

            </button>
        </form>
    </div>
@endsection
