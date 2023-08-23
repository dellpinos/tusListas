@extends('layouts.app')
@section('titulo')
    Nuevo Producto
@endsection

@push('scripts')
    @vite('resources/js/productoForm.js')
@endpush


@section('contenido')
    <div class="md:flex md:justify-center md:gap-10 md:items-center">
        <div class="md:w-6/12 p-5">
            <img src="#" alt="Imagen registro de usuarios">
        </div>

        <div class="md:w-4/12 bg-white p-6 rounded-lg shadow-xl">
            <form action="{{ route('producto.store') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="codigo" class="mb-2 block uppercase text-gray-500 font-bold">Código del producto</label>
                    <input type="text" id="codigo" name="codigo" readonly
                        class=" text-center bg-gray-300 font-bold text-black text-lg border p-3 w-full rounded-lg @error('codigo') border-red-500 @enderror"
                        value="{{ strtoupper($codigo) }}">
                    @error('codigo')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">Nombre del producto</label>
                    <input type="text" id="name" name="name" placeholder="Nombre del producto"
                        class="border p-3 w-full rounded-lg @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="categoria" class="mb-2 block uppercase text-gray-500 font-bold">Categoria</label>
                    <select class="border p-3 w-full rounded-lg @error('categoria') border-red-500 @enderror" id="categoria"
                        name="categoria_id">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach

                        @error('categoria')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="mb-5">
                    <label for="fabricante" class="mb-2 block uppercase text-gray-500 font-bold">Laboratorio -
                        Fabricante</label>
                    <select class="border p-3 w-full rounded-lg @error('fabricante') border-red-500 @enderror"
                        id="fabricante" name="fabricante_id">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($fabricantes as $fabricante)
                            <option value="{{ $fabricante->id }}">{{ $fabricante->nombre }}</option>
                        @endforeach

                        @error('fabricante')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="mb-5">
                    <label for="proveedor" class="mb-2 block uppercase text-gray-500 font-bold">Distribuidora</label>
                    <select class="border p-3 w-full rounded-lg @error('proveedor') border-red-500 @enderror" id="proveedor"
                        name="provider_id">
                        <option value="" selected disabled>- Seleccionar -</option>

                        @foreach ($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                        @endforeach

                        @error('proveedor')
                            <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                        @enderror
                    </select>
                </div>

                <div class="mb-5">
                    <label for="precio" class="mb-2 block uppercase text-gray-500 font-bold">Precio Costo sin IVA</label>
                    <input type="number" id="precio" name="precio" placeholder="Precio de costo en pesos"
                        class="border p-3 w-full rounded-lg @error('precio') border-red-500 @enderror">
                    @error('precio')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-5">
                    <label for="dolar" class="mb-2 block uppercase text-gray-500 font-bold">Cotización dolar Blue
                        (compra)</label>
                    <input type="number" id="dolar" name="dolar" placeholder="Cotización al dia de la fecha"
                        class="border p-3 w-full rounded-lg @error('dolar') border-red-500 @enderror">
                    @error('dolar')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <label for="ganancia" class=" mb-4 block uppercase text-gray-500 font-bold">Ganancia</label>
                <div class="mb-5 flex justify-between border p-3 rounded-md border-gray-200" id="contenedor-radios">
                    <label for="ganancia-categoria" class=" cursor-pointer text-gray-500">Categoria</label>
                    <input type="radio" value="categoria" name="ganancia" class="cursor-pointer"
                        id="ganancia-categoria" />
                    <label for="ganancia-proveedor" class="cursor-pointer text-gray-500">Proveedor</label>
                    <input type="radio" value="proveedor" name="ganancia" class="cursor-pointer" id="ganancia-proveedor"
                        checked />
                    <label for="ganancia-personalizada" class="cursor-pointer text-gray-500">Personalizada</label>
                    <input type="radio" value="" name="ganancia" class="cursor-pointer"
                        id="ganancia-personalizada" />
                </div>
                <div class="mb-5">
                    <input type="number" step="0.1" min="1" id="ganancia" name="ganancia"
                        placeholder="1.2, 1.7, 1.9" disabled
                        class="bg-gray-300 cursor-not-allowed text-gray-500   border p-3 w-full rounded-lg @error('ganancia') border-red-500 @enderror">
                    @error('ganancia')
                        <p class=" bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                    @enderror
                </div>



                <input type="submit" value="Crear Producto"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
        </div>

    </div>
@endsection
