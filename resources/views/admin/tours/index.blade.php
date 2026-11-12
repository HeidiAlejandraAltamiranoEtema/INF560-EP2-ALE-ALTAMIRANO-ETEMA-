<x-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Gestión de Tours</h1>
            <p class="text-slate-600">Panel de administración para crear, modificar o eliminar los tours del sistema.</p>
        </div>
        <a href="{{ route('admin.tours.create') }}" class="bg-amber-800 hover:bg-amber-900 text-white font-bold px-5 py-2.5 rounded-xl transition text-sm shadow text-center">
            + Nuevo Tour
        </a>
    </div>

    @if ($tours->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-500 shadow-sm">
            <span class="text-4xl mb-3 block">🏔️</span>
            <p>No hay tours registrados en el sistema.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Título</th>
                            <th class="py-4 px-6">Categoría</th>
                            <th class="py-4 px-6">Precio</th>
                            <th class="py-4 px-6">Fecha Salida</th>
                            <th class="py-4 px-6">Cupo Máx.</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($tours as $tour)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    <a href="{{ route('tours.show', $tour->slug) }}" class="hover:text-amber-700 transition" target="_blank">
                                        {{ $tour->titulo }}
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                                        {{ $tour->categoria->nombre ?? 'N/D' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-800 font-bold">
                                    Bs. {{ number_format($tour->precio, 2) }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $tour->fecha_salida ? $tour->fecha_salida->format('d/m/Y H:i') : 'N/D' }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $tour->cupo_maximo }}
                                </td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.tours.edit', $tour) }}" class="inline-block bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.tours.destroy', $tour) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este tour?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $tours->links() }}
        </div>
    @endif
</x-layout>