<x-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Catálogo de Tours</h1>
        <p class="text-slate-600">Encuentra tu próxima aventura por Potosí filtrando por categoría o buscando por título.</p>
    </div>

    <!-- Filtros y Búsqueda (R3) -->
    <form method="GET" action="{{ route('tours.index') }}" class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="w-full md:w-1/3">
            <label for="buscar" class="block text-xs font-semibold text-slate-600 mb-1">Buscar por título</label>
            <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" placeholder="Ej. Cerro Rico..." class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
        </div>

        <div class="w-full md:w-1/3">
            <label for="categoria" class="block text-xs font-semibold text-slate-600 mb-1">Filtrar por categoría</label>
            <select name="categoria" id="categoria" class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->slug }}" {{ request('categoria') === $cat->slug ? 'selected' : '' }}>
                        {{ $cat->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-auto flex items-end gap-2 self-end">
            <button type="submit" class="bg-amber-800 hover:bg-amber-900 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
                Filtrar
            </button>
            <a href="{{ route('tours.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-4 py-2 rounded-lg text-sm transition">
                Limpiar
            </a>
        </div>
    </form>

    <!-- Listado de Tours (Paginado de 9 en 9) -->
    @if ($tours->isEmpty())
        <div class="bg-white border border-slate-200 rounded-xl p-12 text-center text-slate-500">
            No se encontraron tours con los criterios seleccionados.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach ($tours as $tour)
                <x-tour-card :tour="$tour" />
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $tours->links() }}
        </div>
    @endif
</x-layout>