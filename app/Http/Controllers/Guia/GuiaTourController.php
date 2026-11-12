<?php

namespace App\Http\Controllers\Guia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuiaTourController extends Controller
{
    public function index()
    {
        $guia = Auth::user();

        // Obtener los tours guiados por este usuario con sus respectivas reservas y turistas (R6)
        $tours = $guia->toursGuiados()
            ->with(['reservas.user', 'categoria'])
            ->orderBy('fecha_salida', 'asc')
            ->get();

        return view('guia.tours', compact('tours'));
    }
}
