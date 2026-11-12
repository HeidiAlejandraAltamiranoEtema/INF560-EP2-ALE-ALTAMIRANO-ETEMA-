<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservaController extends Controller
{
    use AuthorizesRequests;

    // Guardar una nueva reserva hecha por un turista (R7)
    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'cantidad_personas' => 'required|integer|min:1',
            'observacion' => 'nullable|string|max:500',
        ]);

        // Validar que el tour esté activo y con fecha futura
        if (!$tour->activo || $tour->fecha_salida <= now()) {
            return back()->with('error', 'El tour no está disponible para reservas.');
        }

        // Validar que no exceda los cupos disponibles (R1 / R7)
        if ($validated['cantidad_personas'] > $tour->cupos_disponibles) {
            return back()->with('error', 'No hay suficientes cupos disponibles para este tour.');
        }

        Reserva::create([
            'user_id' => Auth::id(),
            'tour_id' => $tour->id,
            'cantidad_personas' => $validated['cantidad_personas'],
            'estado' => 'pendiente',
            'observacion' => $validated['observacion'] ?? null,
        ]);

        return redirect()->route('reservas.mias')->with('success', '¡Reserva realizada con éxito!');
    }

    // Ver las reservas del turista autenticado (R7)
    public function misReservas()
    {
        $reservas = Auth::user()->reservas()->with('tour.categoria')->latest()->paginate(10);
        return view('reservas.mias', compact('reservas'));
    }

    // Cancelar reserva propia y pendiente usando Policy (R7)
    public function cancelar(Reserva $reserva)
    {
        // Autorizar mediante la ReservaPolicy creada
        $this->authorize('cancelar', $reserva);

        $reserva->update(['estado' => 'cancelada']);

        return back()->with('success', 'La reserva ha sido cancelada correctamente.');
    }

    // Listado general de reservas para el Admin (R7)
    //Guia o Admin vistas secundarias:
    public function adminIndex(Request $request)
    {
        $query = Reserva::with(['user', 'tour']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $reservas = $query->latest()->paginate(10);
        return view('admin.reservas.index', compact('reservas'));
    }

    // Cambiar estado de reserva por el Administrador (R7)
    public function adminCambiarEstado(Request $request, Reserva $reserva)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        $reserva->update(['estado' => $validated['estado']]);

        return back()->with('success', 'Estado de la reserva actualizado correctamente.');
    }
}