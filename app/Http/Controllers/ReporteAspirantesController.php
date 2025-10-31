<?php

namespace App\Http\Controllers;

use App\Models\Aspirante;
use App\Models\Diplomado;
use Illuminate\Http\Request;

class ReporteAspirantesController extends Controller
{
    const TIPO_BASICO = 'basico';
    const TIPO_INTERMEDIO_AVANZADO = 'intermedio y avanzado';

    /*
     * Muestra la vista principal del reporte de Aspirantes interesados.
    */
    public function mostrarReporte()
    {
        return view('administrador.reportes.aspirantesInteresados.reporte');
    }

    /*
     * Genera el total de aspirantes interesados en un tipo de diplomado específico.
    */
    public function totalPorDiplomado(Request $request)
    {
        /* Convierte la cadena del tipo de diplomado (ej. 'Básico') a su valor de enumeración (`basico`). */
        $tipoDiplomadoLargo = $request->input('tipo'); 

        if (empty($tipoDiplomadoLargo)) {
             return response()->json(['labels' => ['Selección inválida'], 'data' => [0]]);
        }
        
        $tipoDiplomadoEnum = $this->mapTipoToEnum($tipoDiplomadoLargo);
        /* Obtiene los nombres de todos los diplomados que coinciden con ese tipo. */
        $nombresDiplomados = Diplomado::where('tipo', $tipoDiplomadoEnum)->pluck('nombre');

        /* 3. Cuenta los aspirantes cuyo campo `interes` coincide con esos nombres.*/
        $total = Aspirante::whereIn('interes', $nombresDiplomados)->count();

        return response()->json([
            'labels' => [$tipoDiplomadoLargo],
            'data'   => [$total],
        ]);
    }

    /*
     * Genera datos para una gráfica comparativa del total de aspirantes por tipo de diplomado. Itera sobre los tipos 
        de diplomado predefinidos, cuenta el total de aspirantes interesados en diplomados de cada tipo y devuelve los 
        datos para la gráfica.
    */
    public function comparacionTipos()
    {
        $tipos = [
            'Básico' => self::TIPO_BASICO,
            'Intermedio y Avanzado' => self::TIPO_INTERMEDIO_AVANZADO,
        ];

        $labels = array_keys($tipos);
        $data = [];
        
        foreach ($tipos as $label => $tipoEnum) {
            $nombresDiplomados = Diplomado::where('tipo', $tipoEnum)->pluck('nombre');
            
            $data[] = Aspirante::whereIn('interes', $nombresDiplomados)->count();
        }

        return response()->json([
            'labels' => $labels, 
            'data'   => $data,
        ]);
    }
    

    /*
     * Mapea el nombre largo del tipo de diplomado (ej. 'Básico') a su valor de enumeración
        utilizado en la base de datos (ej. 'basico').
    */
    protected function mapTipoToEnum(string $tipoLargo): string
    {
        if (str_contains(strtolower($tipoLargo), 'básico')) {
            return self::TIPO_BASICO;
        }
        return self::TIPO_INTERMEDIO_AVANZADO;
    }
}