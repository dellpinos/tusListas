@extends('layouts.app')
@section('titulo')
    Nuevo Proveedor
@endsection


@section('contenido')
    <div class="md:flex md:justify-center md:gap-10 md:items-center">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de proveedor">
        </div>

        <div class="md:w-4/12 bg-white p-6 rounded-lg shadow-xl">
            <form action="{{ route('proveedor.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">Nombre del Proveedor</label>
                    <input type="text" id="name" name="name" placeholder="Arcuri, Lepore, Panacea, etc"
                        class="border p-3 w-full rounded-lg @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email de contacto"
                        class="border p-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="telefono" class="mb-2 block uppercase text-gray-500 font-bold">Telefono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Telefono del Laboratorio o Fabricante"
                        class="border p-3 w-full rounded-lg @error('telefono') border-red-500 @enderror"
                        value="{{ old('telefono') }}">
                    @error('telefono')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="vendedor" class="mb-2 block uppercase text-gray-500 font-bold">Nombre del vendendor</label>
                    <input type="text" id="vendedor" name="vendedor" placeholder="Nombre del vendedor"
                        class="border p-3 w-full rounded-lg @error('vendedor') border-red-500 @enderror"
                        value="{{ old('vendedor') }}">
                    @error('vendedor')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="web" class="mb-2 block uppercase text-gray-500 font-bold">Sitio Web</label>
                    <input type="text" id="web" name="web" placeholder="www.arcuri.com.ar"
                        class="border p-3 w-full rounded-lg @error('web') border-red-500 @enderror"
                        value="{{ old('web') }}">
                    @error('web')
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

{{--                 Falta la categoria, provablemente necesite un hidden submit porque es el id de la tabla pivote --}}




                <input type="submit" value="Nuevo Proveedor"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        </div>

    </div>
@endsection
