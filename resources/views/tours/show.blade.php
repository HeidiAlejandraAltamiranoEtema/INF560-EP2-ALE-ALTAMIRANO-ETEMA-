<x-layout>
    <div class="mb-6">
        <a href="{{ route('tours.index') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-900 transition">
            ← Volver al catálogo
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Columna Izquierda: Información del Tour -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="h-80 w-full bg-slate-200 relative">
                    @if ($tour->imagen)
                        <img src="{{ asset('storage/' . $tour->imagen) }}" alt="{{ $tour->titulo }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-amber-900 to-stone-700 text-amber-100 p-6 text-center">
                            <span class="text-5xl mb-2">🏔️</span>
                            <span class="text-sm uppercase tracking-widest opacity-80">{{ $tour->categoria->nombre }}</span>
                        </div>
                    @endif
                    <span class="absolute top-4 right-4 bg-amber-900 text-white text-sm font-bold px-4 py-1.5 rounded-full shadow">
                        Bs. {{ number_format($tour->precio, 2) }}
                    </span>
                </div>

                <div class="p-8">
                    <div class="flex items-center justify-between text-sm text-slate-500 mb-3">
                        <span class="bg-amber-100 text-amber-800 font-bold px-3 py-1 rounded-full text-xs">
                            {{ $tour->categoria->nombre }}
                        </span>
                        <span>Salida: {{ $tour->fecha_salida->format('d/m/Y H:i') }}</span>
                    </div>

                    <h1 class="text-3xl font-extrabold text-slate-900 mb-4">{{ $tour->titulo }}</h1>
                    
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line mb-6">
                        {{ $tour->descripcion }}
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-slate-100 pt-6">
                        <div>
                            <span class="block text-xs text-slate-400 uppercase font-semibold">Duración</span>
                            <span class="text-slate-800 font-bold">{{ $tour->duracion_horas }} horas</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400 uppercase font-semibold">Cupo Máximo</span>
                            <span class="text-slate-800 font-bold">{{ $tour->cupo_maximo }} personas</span>
                        </div>
                        <div>
                            <span class="block text-xs text-slate-400 uppercase font-semibold">Guía Asignado</span>
                            <span class="text-slate-800 font-bold">{{ $tour->guia->name ?? 'Por asignar' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servicios Incluidos -->
            @if ($tour->servicios->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Servicios Incluidos</h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($tour->servicios as $servicio)
                            <li class="flex items-center gap-2 text-sm text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <span class="text-emerald-600 font-bold">✓</span> {{ $servicio->nombre }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Columna Derecha: Panel de Reserva (Turista) -->
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-6">
                <h3 class="text-xl font-bold text-slate-900 mb-4">Reservar Tour</h3>

                <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-100 flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600">Cupos Disponibles:</span>
                    <span class="text-lg font-extrabold {{ $tour->cupos_disponibles > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $tour->cupos_disponibles }}
                    </span>
                </div>

                @auth
                    @role('turista')
                        @if ($tour->activo && $tour->fecha_salida > now() && $tour->cupos_disponibles > 0)
                            <form action="{{ route('reservas.store', $tour) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="cantidad_personas" class="block text-xs font-semibold text-slate-600 mb-1">Cantidad de personas</label>
                                    <input type="number" name="cantidad_personas" id="cantidad_personas" min="1" max="{{ $tour->cupos_disponibles }}" value="1" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                                    @error('cantidad_personas')
                                        <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="observacion" class="block text-xs font-semibold text-slate-600 mb-1">Observación (opcional)</label>
                                    <textarea name="observacion" id="observacion" rows="3" placeholder="Alergias, requerimientos especiales..." class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500"></textarea>
                                </div>

                                <button type="submit" class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3 rounded-xl transition shadow">
                                    Confirmar Reserva
                                </button>
                            </form>
                        @else
                            <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm text-center">
                                Este tour ya no acepta reservas (inactivo, fecha pasada o agotado).
                            </div>
                        @endif
                    @else
                        <div class="p-4 bg-slate-100 text-slate-600 rounded-xl text-sm text-center">
                            Solo los usuarios con rol <strong>turista</strong> pueden realizar reservas.
                        </div>
                    @endrole
                @else
                    <div class="text-center space-y-4">
                        <p class="text-sm text-slate-600">Debes iniciar sesión como turista para poder reservar este tour.</p>
                        <a href="{{ route('login') }}" class="block w-full bg-amber-800 hover:bg-amber-900 text-white font-bold py-3 rounded-xl transition text-center shadow">
                            Iniciar Sesión
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</x-layout>