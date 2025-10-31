<?php

namespace App\Http\Controllers;

use App\Models\Cargo;

class CargoController extends Controller
{
    /*
     * Muestra los detalles de un Cargo específico. Utiliza la inyección de modelo para obtener la 
     * instancia del Cargo y la pasa a la vista.
    */
    public function show(Cargo $cargo)
    {
        return view('cargos.show', compact('cargo'));
    }
}