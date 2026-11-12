<x-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Panel del Guía</h1>
        <p class="text-slate-600">Listado de los tours que tienes asignados y los turistas que realizarán el recorrido contigo.</p>
    </div>

    @if ($tours->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-500 shadow-sm">
            <span class="text-4xl mb-3 block">🏔️</span>
            <p>No tienes ningún tour asignado actualmente.</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach ($tours as $tour)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
                        <div>
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                {{ $tour->categoria->nombre }}
                            </span>
                            <h2 class="text-2xl font-bold text-slate-900 mt-2">{{ $tour->titulo }}</h2>
                            <p class="text-sm text-slate-500 mt-1">Salida: {{ $tour->fecha_salida->format('d/m/Y H:i') }} ({{ $tour->duracion_horas }} horas)</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-200 px-4 py-3 rounded-xl text-center">
                            <span class="block text-xs text-slate-400 uppercase font-semibold">Cupos Ocupados</span>
                            <span class="text-lg font-bold text-amber-800">
                                {{ $tour->reservas->where('estado', 'confirmada')->sum('cantidad_personas') }} / {{ $tour->cupo_maximo }}
                            </span>
                        </div>
                    </div>

                    <!-- Lista de Turistas Reservados -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Turistas Inscritos en este Tour</h3>

                        @if ($tour->reservas->isEmpty())
                            <p class="text-sm text-slate-500 italic bg-slate-50 p-4 rounded-xl">Aún no hay reservas registradas para este tour.</p>
                        @else
                            <div class="bg-slate-50 rounded-xl overflow-hidden border border-slate-200">
                                <table class="w-full text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-200 text-xs text-slate-500 uppercase">
                                            <th class="py-3 px-4">Turista</th>
                                            <th class="py-3 px-4">Personas</th>
                                            <th class="py-3 px-4">Estado</th>
                                            <th class="py-3 px-4">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach ($tour->reservas as $reserva)
                                            <tr>
                                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $reserva->user->name }}</td>
                                                <td class="py-3 px-4 text-slate-600">{{ $reserva->cantidad_personas }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full 
                                                        {{ $reserva->estado === 'confirmada' ? 'bg-emerald-100 text-emerald-800' : ($reserva->estado === 'pendiente' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ ucfirst($reserva->estado) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4 text-slate-500 text-xs">{{ $reserva->observacion ?? 'Ninguna' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>