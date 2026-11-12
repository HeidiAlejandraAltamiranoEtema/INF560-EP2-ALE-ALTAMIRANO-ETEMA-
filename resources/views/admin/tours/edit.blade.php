<x-layout>
    <div class="mb-6">
        <a href="{{ route('admin.tours.index') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-900 transition">
            ← Volver a la gestión de tours
        </a>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <h1 class="text-2xl font-extrabold text-slate-900 mb-6">Editar Tour: {{ $tour->titulo }}</h1>

        <form action="{{ route('admin.tours.update', $tour) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="md:col-span-2">
                    <label for="titulo" class="block text-xs font-semibold text-slate-600 mb-1">Título del Tour</label>
                    <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tour->titulo) }}" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                    @error('titulo') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria_id" class="block text-xs font-semibold text-slate-600 mb-1">Categoría</label>
                    <select name="categoria_id" id="categoria_id" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Seleccione categoría</option>
                        @foreach (\App\Models\Categoria::all() as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $tour->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Guía Asignado -->
                <div>
                    <label for="guia_id" class="block text-xs font-semibold text-slate-600 mb-1">Guía Asignado</label>
                    <select name="guia_id" id="guia_id" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                        <option value="">Seleccione guía</option>
                        @foreach (\App\Models\User::role('guia')->get() as $guia)
                            <option value="{{ $guia->id }}" {{ old('guia_id', $tour->guia_id) == $guia->id ? 'selected' : '' }}>{{ $guia->name }}</option>
                        @endforeach
                    </select>
                    @error('guia_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-xs font-semibold text-slate-600 mb-1">Precio (Bs.)</label>
                    <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio', $tour->precio) }}" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                    @error('precio') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Cupo Máximo -->
                <div>
                    <label for="cupo_maximo" class="block text-xs font-semibold text-slate-600 mb-1">Cupo Máximo</label>
                    <input type="number" name="cupo_maximo" id="cupo_maximo" value="{{ old('cupo_maximo', $tour->cupo_maximo) }}" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                    @error('cupo_maximo') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Duración (Horas) -->
                <div>
                    <label for="duracion_horas" class="block text-xs font-semibold text-slate-600 mb-1">Duración (Horas)</label>
                    <input type="number" name="duracion_horas" id="duracion_horas" value="{{ old('duracion_horas', $tour->duracion_horas) }}" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                    @error('duracion_horas') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha y Hora de Salida -->
                <div>
                    <label for="fecha_salida" class="block text-xs font-semibold text-slate-600 mb-1">Fecha y Hora de Salida</label>
                    <input type="datetime-local" name="fecha_salida" id="fecha_salida" value="{{ old('fecha_salida', optional($tour->fecha_salida)->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">
                    @error('fecha_salida') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Imagen -->
                <div class="md:col-span-2">
                    <label for="imagen" class="block text-xs font-semibold text-slate-600 mb-1">Reemplazar Imagen (Opcional)</label>
                    <input type="file" name="imagen" id="imagen" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    @error('imagen') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-xs font-semibold text-slate-600 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="4" required class="w-full rounded-lg border-slate-300 border px-3 py-2 text-sm focus:ring-amber-500 focus:border-amber-500">{{ old('descripcion', $tour->descripcion) }}</textarea>
                    @error('descripcion') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.tours.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-5 py-2.5 rounded-xl text-sm transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-amber-800 hover:bg-amber-900 text-white font-bold px-6 py-2.5 rounded-xl text-sm transition shadow">
                    Actualizar Tour
                </button>
            </div>
        </form>
    </div>
</x-layout>