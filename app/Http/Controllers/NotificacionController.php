<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /*
     * Muestra el índice de notificaciones del usuario autenticado.
    */
    public function index(Request $request)
    {
        $user = Auth::user();

        /*Determina el layout a usar ('layouts.encabezados' para admin/coord, 'layouts.encabezadosAl' para alumnos/aspirantes).*/
        $layout = $this->layoutFor($user);

        $estado = $request->get('estado', 'all');
        $query  = $user->notifications()->latest();

        /* Permite filtrar las notificaciones por estado ('all', 'read', 'unread'). */
        if ($estado === 'unread') {
            $query->whereNull('read_at');
        } elseif ($estado === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();

        $idsVisiblesNoLeidas = $notifications->getCollection()
            ->whereNull('read_at')
            ->pluck('id');

        if ($idsVisiblesNoLeidas->isNotEmpty()) {
            $user->notifications()
                ->whereIn('id', $idsVisiblesNoLeidas)
                ->update(['read_at' => now()]);

            $notifications->getCollection()->transform(function ($n) {
                $n->read_at = now();
                return $n;
            });
        }

        return view('notificaciones.index', compact('notifications', 'estado', 'layout'));
    }

    /*
     * Determina el layout principal a utilizar en función del rol del usuario.
    */
    private function layoutFor($user): string
    {
        $rol = optional($user->rol)->nombre_rol;
        $isAdminLike = in_array(strtolower((string)$rol), ['administrador', 'coordinador', 'superadmin'], true);

        return $isAdminLike ? 'layouts.encabezados' : 'layouts.encabezadosAl';
    }


    /*
     * Marca una notificación específica como leída. Busca la notificación por ID para el usuario autenticado 
        y la marca como leída si no lo estaba.
    */
    public function markOne($id)
    {
        $n = Auth::user()->notifications()->findOrFail($id);
        if (is_null($n->read_at)) $n->markAsRead();
        return back();
    }

    /*
     * Marca todas las notificaciones no leídas del usuario autenticado como leídas.
    */
    public function markAll()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    }

    /*
     * Elimina una notificación específica del usuario autenticado.
    */
    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        return back();
    }
}
