<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TusListas - @yield('titulo')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

        @stack('styles')
        @vite('resources/js/app.js')
        @vite('resources/css/app.css')
</head>

<body class=" bg-home">
    <header class=" p-5 bg-gray-800 shadow-gray-400">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('login') }}">
                <h1 class=" header__logo">Tus Listas</h1>
            </a>
            @auth
                <nav class="header__nav">

                    <a></a>
                    <a class=" font-bold text-white mx-2" href="#">
                        Hola: <span class=" font-normal ">{{ auth()->user()->username }}</span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class=" hover:text-white font-bold uppercase text-gray-600">Cerrar
                            sesión</button>
                    </form>
                </nav>
            @endauth
            @guest
                <nav class="header__nav">
                    <a class=" font-bold uppercase text-gray-600 hover:text-white"
                        href="{{ route('login') }}">Login</a>
                </nav>
            @endguest
        </div>
    </header>

    <main class=" container mx-auto mt-10">
        <h2 class="">
            @yield('titulo')
        </h2>

        @yield('contenido')

    </main>

    <footer class="footer__contenedor">
        MdP - Todos los derechos reservados {{ now()->year }}

    </footer>
    @stack('scripts')


</body>

</html>
