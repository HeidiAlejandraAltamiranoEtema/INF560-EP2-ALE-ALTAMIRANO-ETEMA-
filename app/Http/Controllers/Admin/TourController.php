<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Categoria;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TourController extends Controller
{
    // Listado paginado de tours para el admin (R4)
    public function index()
    {
        $tours = Tour::with(['categoria', 'guia', 'servicios'])->latest()->paginate(10);
        return view('admin.tours.index', compact('tours'));
    }

    // Formulario de creación (R4)
    public function create()
    {
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $guias = \App\Models\User::role('guia')->get();

        return view('admin.tours.create', compact('categorias', 'servicios', 'guias'));
    }

    // Guardar nuevo tour con validación e imagen (R4, R5)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|unique:tours,titulo|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'guia_id' => 'nullable|exists:users,id',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0.01',
            'duracion_horas' => 'required|integer|min:1',
            'cupo_maximo' => 'required|integer|between:1,50',
            'fecha_salida' => 'required|date|after:today',
            'servicios' => 'array',
            'servicios.*' => 'exists:servicios,id',
            'imagen' => 'nullable|image|mimes:jpg,png,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['titulo']);
        // Por defecto activo en true para que aparezca en el catálogo público de inmediato
        $validated['activo'] = $request->has('activo') ? true : true; 

        // Manejo de imagen (R5)
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('tours', 'public');
        }

        $tour = Tour::create($validated);

        // Sincronizar servicios (N:M)
        if ($request->filled('servicios')) {
            $tour->servicios()->sync($request->servicios);
        }

        return redirect()->route('admin.tours.index')->with('success', 'Tour creado exitosamente.');
    }

    // Formulario de edición (R4)
    public function edit(Tour $tour)
    {
        $categorias = Categoria::all();
        $servicios = Servicio::all();
        $guias = \App\Models\User::role('guia')->get();
        
        $tour->load('servicios');

        return view('admin.tours.edit', compact('tour', 'categorias', 'servicios', 'guias'));
    }

    // Actualizar tour y reemplazar imagen si existe (R4, R5)
    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255|unique:tours,titulo,' . $tour->id,
            'categoria_id' => 'required|exists:categorias,id',
            'guia_id' => 'nullable|exists:users,id',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0.01',
            'duracion_horas' => 'required|integer|min:1',
            'cupo_maximo' => 'required|integer|between:1,50',
            'fecha_salida' => 'required|date',
            'servicios' => 'array',
            'servicios.*' => 'exists:servicios,id',
            'imagen' => 'nullable|image|mimes:jpg,png,webp|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['titulo']);
        $validated['activo'] = $request->has('activo');

        // Reemplazar imagen (R5)
        if ($request->hasFile('imagen')) {
            if ($tour->imagen && Storage::disk('public')->exists($tour->imagen)) {
                Storage::disk('public')->delete($tour->imagen);
            }
            $validated['imagen'] = $request->file('imagen')->store('tours', 'public');
        }

        $tour->update($validated);

        // Sincronizar servicios
        $tour->servicios()->sync($request->input('servicios', []));

        return redirect()->route('admin.tours.index')->with('success', 'Tour actualizado correctamente.');
    }

    // Eliminar tour con validación de reservas confirmadas (R4, R5)
    public function destroy(Tour $tour)
    {
        // Regla: No se puede eliminar si tiene reservas confirmadas
        $tieneReservasConfirmadas = $tour->reservas()->where('estado', 'confirmada')->exists();

        if ($tieneReservasConfirmadas) {
            return back()->with('error', 'No se puede eliminar el tour porque tiene reservas confirmadas.');
        }

        // Borrar imagen del disco si existe (R5)
        if ($tour->imagen && Storage::disk('public')->exists($tour->imagen)) {
            Storage::disk('public')->delete($tour->imagen);
        }

        $tour->servicios()->detach();
        $tour->delete();

        return redirect()->route('admin.tours.index')->with('success', 'Tour eliminado correctamente.');
    }
}