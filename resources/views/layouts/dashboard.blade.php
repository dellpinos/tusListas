<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TusListas - @yield('titulo')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('styles')
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class=" bg-gray-700 overflow-hidden">
    <header class=" p-5 bg-gray-800 shadow-gray-400">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#">
                <h1 class=" text-3xl font-black text-white">Tus Listas</h1>
            </a>
            @auth
                <nav class="flex gap-2 items-center">

                    <a></a>
                    <a class=" font-bold text-white text-sm mx-2" href="#">
                        Hola: <span class=" font-normal ">{{ auth()->user()->username }}</span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class=" hover:text-white font-bold uppercase text-gray-600 text-sm">Cerrar
                            sesión</button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="flex gap-2 items-center">
                    <a class=" font-bold uppercase text-gray-600 text-sm hover:text-white"
                        href="{{ route('login') }}">Login</a>
                </nav>
            @endguest
        </div>
    </header>

    <main class="flex" >
        <aside class="w-1/8 h-screen bg-gray-800 p-4">
            <nav class="space-y-2 grid">
                <a href="#" class="grid text-center p-2 mb-2"><i class="fa-solid fa-plus"></i>Nuevo Producto</a>
                <a href="#" class=" sidebar activo grid text-center"><i class="fa-solid fa-user text-4xl"></i>Nuevo Proveedor</a>
                <a href="#"><i class="fa-solid fa-user icono"></i>Nueva Categoria</a>
                <a href="#" class=" sidebar__icono">Nuevo Fabricante</a>
                <a href="#">Buscador</a>
            </nav>
        </aside>

        <div class="w-3/4 h-screen overflow-y-auto p-4 container mt-10 mx-auto">

            <h2 class=" font-black text-white text-5xl mb-10 text-center">
                @yield('titulo')
            </h2>
    
            @yield('contenido')
        </div>
        

    </main>

    <footer class="mt-10 text-center p-5 text-gray-500 font-bold uppercase">
        MdP - Todos los derechos reservados {{ now()->year }}

    </footer>
    @stack('scripts')


</body>

</html>
