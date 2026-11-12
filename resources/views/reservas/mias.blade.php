<x-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Mis Reservas</h1>
        <p class="text-slate-600">Aquí puedes consultar el estado de tus reservas y cancelar las pendientes si lo necesitas.</p>
    </div>

    @if ($reservas->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-500 shadow-sm">
            <span class="text-4xl mb-3 block">🧳</span>
            <p class="mb-4">Aún no has realizado ninguna reserva de tours.</p>
            <a href="{{ route('tours.index') }}" class="inline-block bg-amber-800 hover:bg-amber-900 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm">
                Explorar Catálogo
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Tour</th>
                            <th class="py-4 px-6">Fecha de Salida</th>
                            <th class="py-4 px-6">Personas</th>
                            <th class="py-4 px-6">Estado</th>
                            <th class="py-4 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($reservas as $reserva)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    <a href="{{ route('tours.show', $reserva->tour->slug) }}" class="hover:text-amber-700 transition">
                                        {{ $reserva->tour->titulo }}
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $reserva->tour->fecha_salida->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-4 px-6 text-slate-600">
                                    {{ $reserva->cantidad_personas }}
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $estados = [
                                            'pendiente' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'confirmada' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'cancelada' => 'bg-red-100 text-red-800 border-red-200',
                                        ];
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full border {{ $estados[$reserva->estado] ?? 'bg-slate-100 text-slate-800' }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    @can('cancelar', $reserva)
                                        <form action="{{ route('reservas.cancelar', $reserva) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-xs bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                                Cancelar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 italic">No disponible</span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $reservas->links() }}
        </div>
    @endif
</x-layout>