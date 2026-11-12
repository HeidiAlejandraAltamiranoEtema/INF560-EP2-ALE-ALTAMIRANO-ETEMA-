<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TuriPotosí · Descubre Potosí' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-amber-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo y links públicos -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-xl font-bold tracking-wider text-amber-200">
                        TuriPotosí
                    </a>
                    <a href="{{ route('tours.index') }}" class="hover:text-amber-300 transition text-sm font-medium">
                        Catálogo de Tours
                    </a>

                    @auth
                        @role('turista')
                            <a href="{{ route('reservas.mias') }}" class="hover:text-amber-300 transition text-sm font-medium">
                                Mis Reservas
                            </a>
                        @endrole

                        @role('guia')
                            <a href="{{ route('guia.tours') }}" class="hover:text-amber-300 transition text-sm font-medium">
                                Mis Tours (Guía)
                            </a>
                        @endrole

                        @role('admin')
                            <a href="{{ route('admin.tours.index') }}" class="hover:text-amber-300 transition text-sm font-medium">
                                Gestión Tours
                            </a>
                            <a href="{{ route('admin.reservas.index') }}" class="hover:text-amber-300 transition text-sm font-medium">
                                Gestión Reservas
                            </a>
                        @endrole
                    @endauth
                </div>

                <!-- Botones de Sesión -->
                <div class="flex items-center space-x-4 text-sm">
                    @guest
                        <a href="{{ route('login') }}" class="hover:text-amber-200">Iniciar sesión</a>
                        <a href="{{ route('register') }}" class="bg-amber-600 hover:bg-amber-500 px-3 py-1.5 rounded-md font-semibold transition">
                            Registrarse
                        </a>
                    @else
                        <span class="text-amber-200 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-amber-800 hover:bg-amber-700 px-3 py-1.5 rounded-md text-xs font-semibold transition cursor-pointer">
                                Cerrar sesión
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <x-flash />
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-center py-6 text-sm mt-auto">
        <p>© {{ date('Y') }} TuriPotosí — Todos los derechos reservados · Potosí, Bolivia.</p>
    </footer>

</body>
</html>