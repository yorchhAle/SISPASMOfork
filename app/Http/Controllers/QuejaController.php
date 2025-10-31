<?php

namespace App\Http\Controllers;

use App\Models\Queja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuejaController extends Controller
{
    /*
     * Muestra la vista del formulario para crear una nueva Queja o Sugerencia.
    */
    public function create()
    {
        return view('quejas.create');
    }


    /*
     * Almacena una nueva Queja o Sugerencia en la base de datos.
    */
    public function store(Request $request)
    {
        /* Valida el tipo, el mensaje y el contacto. */
        $data = $request->validate([
            'tipo' => ['required', 'in:queja,sugerencia'],
            'mensaje' => ['required', 'string', 'min:10', 'max:5000'],
            'contacto' => ['nullable', 'string', 'max:100'],
        ], [
            'tipo.required' => 'Selecciona si es queja o sugerencia.',
            'mensaje.min' => 'Cuéntanos con un poco más de detalle (mínimo 10 caracteres).',
        ]);

        /* Asigna el ID del usuario autenticado y establece el estatus inicial a 'Pendiente'. */
        $data['id_usuario'] = Auth::id();
        $data['estatus']    = 'Pendiente';

        Queja::create($data);

        return redirect()->route('quejas.propias')
            ->with('ok', "¡Gracias! Recibimos tu {$data['tipo']}.");
    }


    /*
     * Muestra un listado paginado de las Quejas y Sugerencias creadas por el usuario autenticado.
    */
    public function mine()
    {
        $quejas = Queja::where('id_usuario', Auth::id())
            ->orderByDesc('id_queja')
            ->paginate(10);

        return view('quejas.quejaspropias', compact('quejas'));
    }


    /*
     * Muestra los detalles de una Queja específica.
    */
    public function show(Queja $queja)
    {
        /* Llama a `isAllowedToView` para verificar si el usuario es el dueño de la queja o un Administrador. */
        if (!$this->isAllowedToView($queja)) {
            abort(403, 'No tienes permiso para ver esta queja.');
        }

        /* Carga la relación `usuario` para mostrar quién la generó. */
        $queja->load('usuario');
        return view('administrador.CRUDQuejas.read', compact('queja'));
    }


    /*
     * Muestra un listado paginado de todas las Quejas y Sugerencias (Uso administrativo). Permite filtrar por `tipo`, 
        `estatus` y realizar búsquedas generales (`q`) en los mensajes o en los datos del usuario que la generó.
    */
    public function index(Request $request)
    {
        $query = Queja::with('usuario')->orderByDesc('id_queja');

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        if ($request->filled('q')) {
            $term = "%{$request->q}%";
            $query->where(function($q) use ($term) {
                $q->where('mensaje', 'like', $term)
                    ->orWhere('contacto', 'like', $term)
                    ->orWhereHas('usuario', function($userQuery) use ($term) {
                        $userQuery->where('nombre', 'like', $term)
                            ->orWhere('apellidoP', 'like', $term)
                            ->orWhere('apellidoM', 'like', $term)
                            ->orWhere('usuario', 'like', $term)
                            ->orWhere('correo', 'like', $term);
                    });
            });
        }

        $quejas = $query->paginate(15)->appends($request->query());

        return view('administrador.CRUDQuejas.read', compact('quejas'));
    }


    /*
     * Muestra el formulario para editar una Queja o Sugerencia (Uso administrativo). Se usa principalmente para 
        actualizar el estatus o la información de contacto.
    */
    public function edit(Queja $queja)
    {
        return view('administrador.CRUDQuejas.update', compact('queja'));
    }


    /*
     * Actualiza el estatus u otra información de una Queja o Sugerencia (Uso administrativo).
    */
    public function update(Request $request, Queja $queja)
    {
        /* Valida el nuevo `estatus` (solo 'Pendiente' o 'Atendido'). */
        $data = $request->validate([
            'estatus'  => ['required', Rule::in(['Pendiente', 'Atendido'])],
            'contacto' => ['nullable', 'string', 'max:100'],
            'mensaje'  => ['nullable', 'string', 'min:10', 'max:5000'],
        ], [
            'estatus.in' => 'estatus inválido.',
        ]);

        /* Actualiza el registro en la base de datos. */
        $queja->update($data);

        return redirect()->route('quejas.index')->with('success', 'Actualizado.');
    }


    /*
     * Elimina una Queja o Sugerencia específica (Uso administrativo).
    */
    public function destroy(Queja $queja)
    {
        $queja->delete();
        return redirect()->route('quejas.index')->with('success', 'Eliminado.');
    }


    /*
     * Determina si el usuario autenticado tiene permiso para ver una Queja. El permiso se otorga si el
        usuario es el creador de la queja (`isOwner`) o si tiene el rol de `Administrador`.
    */
    protected function isAllowedToView(Queja $queja): bool
    {
        $user = Auth::user();
        $isOwner = $queja->id_usuario === $user->id;
        $isAdmin = $user?->rol?->nombre_rol === 'Administrador';

        return $isOwner || $isAdmin;
    }
}