@extends('layouts.app')
@section('titulo')
    Nuevo Laboratorio - Fabricante
@endsection


@section('contenido')
    <div class="md:flex md:justify-center md:gap-10 md:items-center">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de fabricante">
        </div>

        <div class="md:w-4/12 bg-white p-6 rounded-lg shadow-xl">
            <form action="{{ route('fabricante.store') }}" method="POST">
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
                    <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Notas o descripción"
                        class="border p-3 w-full rounded-lg @error('descripcion') border-red-500 @enderror"></textarea>
                    @error('descripcion')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>



                <input type="submit" value="Nuevo Fabricante"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        </div>

    </div>
@endsection
