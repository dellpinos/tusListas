@extends('layouts.app')
@section('titulo')
    Nuevo Laboratorio - Fabricante
@endsection


@section('contenido')
    <div class="md:flex md:justify-center md:gap-10 md:items-center">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de usuarios">
        </div>

        <div class="md:w-4/12 bg-white p-6 rounded-lg shadow-xl">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">Nombre del Laboratiorio - Fabricante</label>
                    <input type="text" id="name" name="name" placeholder="Holliday, Brower, Importado, etc"
                        class="border p-3 w-full rounded-lg @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email del v"
                        class="border p-3 w-full rounded-lg @error('categoria') border-red-500 @enderror"
                        value="{{ old('categoria') }}">
                    @error('categoria')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="fabricante" class="mb-2 block uppercase text-gray-500 font-bold">Laboratorio -
                        Fabricante</label>
                    <input type="text" id="fabricante" name="fabricante" placeholder="Holliday, Brower, Importado, etc"
                        class="border p-3 w-full rounded-lg @error('fabricante') border-red-500 @enderror"
                        value="{{ old('fabricante') }}">
                    @error('fabricante')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="distribuidora" class="mb-2 block uppercase text-gray-500 font-bold">Distribuidora</label>
                    <input type="text" id="distribuidora" name="distribuidora" placeholder="Arcuri, Lepore, Panacea, etc"
                        class="border p-3 w-full rounded-lg @error('distribuidora') border-red-500 @enderror">
                    @error('distribuidora')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="precio" class="mb-2 block uppercase text-gray-500 font-bold">Precio Costo</label>
                    <input type="number" id="precio" name="precio" placeholder="Precio de costo"
                        class="border p-3 w-full rounded-lg @error('precio') border-red-500 @enderror">
                    @error('precio')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="dolar" class="mb-2 block uppercase text-gray-500 font-bold">Cotización dolar</label>
                    <input type="number" id="dolar" name="dolar" placeholder="Cotización el dia de la fecha"
                        class="border p-3 w-full rounded-lg @error('dolar') border-red-500 @enderror">
                    @error('dolar')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>


                <input type="submit" value="Crear Producto"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        </div>

    </div>
@endsection
