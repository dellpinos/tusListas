<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">



    <title>TusListas - @yield('titulo')</title>

   @vite('resources/css/app.css')
</head>

<body class=" bg-gray-700">
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
                        <button type="submit" class=" hover:text-white font-bold uppercase text-gray-600 text-sm">Cerrar sesión</button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="flex gap-2 items-center">
                    <a class=" font-bold uppercase text-gray-600 text-sm hover:text-white" href="{{ route('login') }}">Login</a>
                </nav>
            @endguest


        </div>
    </header>

    <main class=" container mx-auto mt-10">
        <h2 class=" font-black text-3xl mb-10 text-center">
            @yield('titulo')
        </h2>

        @yield('contenido')

    </main>

    <footer class="mt-10 text-center p-5 text-gray-500 font-bold uppercase">
        MdP - Todos los derechos reservados {{ now()->year }}

    </footer>


</body>

</html>