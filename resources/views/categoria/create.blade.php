@extends('layouts.app')
@section('titulo')
    Nueva Categoria
@endsection


@section('contenido')
    <div class="md:flex md:justify-center md:gap-10 md:items-center">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de categoria">
        </div>

        <div class="md:w-4/12 bg-white p-6 rounded-lg shadow-xl">
            <form action="{{ route('categoria.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">Nombre de la categoria</label>
                    <input type="text" id="name" name="name" placeholder="Acuario, Farmacos, Pet Shop, etc"
                        class="border p-3 w-full rounded-lg @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="ganancia" class="mb-2 block uppercase text-gray-500 font-bold">Ganancia</label>
                    <input type="text" id="ganancia" name="ganancia" placeholder="1.5, 1.8, 1.9"
                        class="border p-3 w-full rounded-lg @error('ganancia') border-red-500 @enderror"
                        value="{{ old('ganancia') }}">
                    @error('ganancia')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

{{--                 Falta el proveedor, provablemente necesite un hidden submit porque es el id de la tabla pivote --}}


                <input type="submit" value="Nueva Categoria"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        </div>

    </div>
@endsection
