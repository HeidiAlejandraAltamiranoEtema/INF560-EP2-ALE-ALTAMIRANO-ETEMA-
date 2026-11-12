<x-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-1">Gestión de Reservas</h1>
            <p class="text-slate-600">Administra y aprueba las reservas realizadas por los turistas.</p>
        </div>

        <!-- Filtro por estado -->
        <form method="GET" action="{{ route('admin.reservas.index') }}" class="flex items-center gap-2">
            <select name="estado" onchange="this.form.submit()" class="rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
            @if(request('estado'))
                <a href="{{ route('admin.reservas.index') }}" class="text-xs text-amber-700 hover:underline">Limpiar</a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if ($reservas->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center text-slate-500 shadow-sm">
            <span class="text-4xl mb-3 block">🎫</span>
            <p>No hay reservas registradas con los filtros seleccionados.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="py-4 px-6">ID / Fecha</th>
                            <th class="py-4 px-6">Turista</th>
                            <th class="py-4 px-6">Tour</th>
                            <th class="py-4 px-6 text-center">Personas</th>
                            <th class="py-4 px-6">Estado</th>
                            <th class="py-4 px-6">Acciones / Cambiar Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach ($reservas as $reserva)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-4 px-6 text-slate-600">
                                    <span class="font-bold text-slate-900">#{{ $reserva->id }}</span>
                                    <span class="block text-xs text-slate-400">{{ $reserva->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-900">
                                    {{ $reserva->user->name ?? ($reserva->turista->name ?? 'Usuario N/D') }}
                                    <span class="block text-xs font-normal text-slate-500">{{ $reserva->user->email ?? ($reserva->turista->email ?? '') }}</span>
                                </td>
                                <td class="py-4 px-6 text-slate-700">
                                    <a href="{{ route('tours.show', $reserva->tour->slug ?? '#') }}" target="_blank" class="hover:text-amber-700 font-medium">
                                        {{ $reserva->tour->titulo ?? 'Tour N/D' }}
                                    </a>
                                </td>
                                <td class="py-4 px-6 text-slate-800 font-bold text-center">
                                    {{ $reserva->cantidad_personas }}
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $badgeColor = match($reserva->estado) {
                                            'confirmada' => 'bg-emerald-100 text-emerald-800',
                                            'cancelada' => 'bg-red-100 text-red-800',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $badgeColor }}">
                                        {{ $reserva->estado }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('admin.reservas.estado', $reserva) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="estado" class="rounded-lg border-slate-300 border px-2 py-1 text-xs focus:ring-amber-500 focus:border-amber-500 bg-white">
                                            <option value="pendiente" {{ $reserva->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $reserva->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="cancelada" {{ $reserva->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition shadow-sm shrink-0">
                                            Guardar
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
            {{ $reservas->links() }}
        </div>
    @endif
</x-layout>