<x-layout>
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-amber-900 to-stone-800 rounded-2xl text-white p-8 md:p-12 mb-12 shadow-lg relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="bg-amber-600 text-amber-100 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                TuriPotosí · Descubre la historia
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold mt-4 mb-4 leading-tight">
                Explora los encantos ocultos de la Villa Imperial
            </h1>
            <p class="text-amber-100 text-base md:text-lg mb-8 leading-relaxed">
                Vive experiencias inolvidables recorriendo el Cerro Rico, la Casa de la Moneda, las lagunas del Kari Kari y mucho más de la mano de guías expertos.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('tours.index') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-6 py-3 rounded-xl transition shadow">
                    Ver Catálogo de Tours
                </a>
                @guest
                    <a href="{{ route('register') }}" class="bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-xl transition border border-white/20">
                        Registrarse como Turista
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <!-- Tours Próximos (R3) -->
    <div class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Tours Próximos Disponibles</h2>
            <a href="{{ route('tours.index') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-900 transition">
                Ver todos →
            </a>
        </div>

        @if ($toursProximos->isEmpty())
            <div class="bg-white border border-slate-200 rounded-xl p-8 text-center text-slate-500">
                No hay tours próximos disponibles en este momento. ¡Vuelve pronto!
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($toursProximos as $tour)
                    <x-tour-card :tour="$tour" />
                @endforeach
            </div>
        @endif
    </div>
</x-layout>