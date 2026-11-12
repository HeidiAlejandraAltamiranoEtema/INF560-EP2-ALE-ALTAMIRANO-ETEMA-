<?php

use Illuminate\Support\Facades\Route;
use App\Models\Tour;
use App\Http\Controllers\Guia\GuiaTourController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\Admin\TourController as AdminTourController;

// ==========================================
// Rutas Públicas (R3)
// ==========================================
Route::get('/', function () {
    $toursProximos = Tour::with(['categoria', 'guia'])
        ->activos()
        ->orderBy('fecha_salida', 'asc')
        ->take(3)
        ->get();
    
    return view('welcome', compact('toursProximos'));
})->name('home');

Route::get('/tours', function (\Illuminate\Http\Request $request) {
    $query = Tour::with(['categoria', 'guia', 'servicios'])->activos();

    // Filtro por categoría (slug)
    if ($request->filled('categoria')) {
        $query->whereHas('categoria', function ($q) use ($request) {
            $q->where('slug', $request->categoria);
        });
    }

    // Búsqueda por título
    if ($request->filled('buscar')) {
        $query->where('titulo', 'like', '%' . $request->buscar . '%');
    }

    $tours = $query->orderBy('fecha_salida', 'asc')->paginate(9)->withQueryString();
    $categorias = \App\Models\Categoria::all();

    return view('tours.index', compact('tours', 'categorias'));
})->name('tours.index');

Route::get('/tours/{tour:slug}', function (Tour $tour) {
    $tour->load(['categoria', 'guia', 'servicios', 'reservas']);
    return view('tours.show', compact('tour'));
})->name('tours.show');


// ==========================================
// Rutas Protegidas para Turistas (R7)
// ==========================================
Route::middleware(['auth', 'role:turista'])->group(function () {
    Route::post('/tours/{tour}/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/mis-reservas', [ReservaController::class, 'misReservas'])->name('reservas.mias');
    Route::patch('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
});


// ==========================================
// Rutas Protegidas para Guías (R6)
// ==========================================
Route::middleware(['auth', 'role:guia'])->prefix('guia')->name('guia.')->group(function () {
    Route::get('/tours', [GuiaTourController::class, 'index'])->name('tours');
});


// ==========================================
// Rutas Protegidas para Administradores (R4, R5, R7)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD de Tours (Resource sin show - R4)
    Route::resource('tours', AdminTourController::class)->except(['show']);

    // Gestión de Reservas (R7)
    Route::get('/reservas', [ReservaController::class, 'adminIndex'])->name('reservas.index');
    Route::patch('/reservas/{reserva}/estado', [ReservaController::class, 'adminCambiarEstado'])->name('reservas.estado');
});


// ==========================================
// Autenticación por defecto de Laravel
// ==========================================
require __DIR__.'/auth.php';