@props(['tour'])

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col hover:shadow-md transition">
    <!-- Imagen o Placeholder (R5) -->
    <div class="h-48 w-full bg-slate-200 relative overflow-hidden">
        @if ($tour->imagen)
            <img src="{{ asset('storage/' . $tour->imagen) }}" alt="{{ $tour->titulo }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-800 to-stone-700 text-amber-100 p-4 text-center">
                <span class="text-3xl mb-1">🏔️</span>
                <span class="text-xs uppercase tracking-widest opacity-80">{{ $tour->categoria->nombre }}</span>
            </div>
        @endif

        <span class="absolute top-3 right-3 bg-amber-900/90 text-white text-xs font-bold px-2.5 py-1 rounded-full">
            Bs. {{ number_format($tour->precio, 2) }}
        </span>
    </div>

    <!-- Cuerpo de la Tarjeta -->
    <div class="p-5 flex-grow flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                <span class="font-semibold text-amber-800">{{ $tour->categoria->nombre }}</span>
                <span>{{ $tour->fecha_salida->format('d/m/Y H:i') }}</span>
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug">
                {{ $tour->titulo }}
            </h3>

            <p class="text-sm text-slate-600 line-clamp-2 mb-4">
                {{ $tour->descripcion }}
            </p>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
            <span class="text-xs font-medium {{ $tour->cupos_disponibles > 0 ? 'text-emerald-700' : 'text-red-600' }}">
                {{ $tour->cupos_disponibles }} cupos libres
            </span>

            <a href="{{ route('tours.show', $tour->slug) }}" class="text-sm font-semibold text-amber-700 hover:text-amber-900 transition">
                Ver detalles →
            </a>
        </div>
    </div>
</div>