<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\Viaje;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function verChat(Viaje $viaje)
    {
        $userId = Auth::id();

        $mensajes = Mensaje::where('viaje_id', $viaje->id)
            ->where(function ($q) use ($userId) {
                $q->where('emisor_id', $userId)
                  ->orWhere('receptor_id', $userId);
            })
            ->orderBy('created_at')
            ->get();

        return view('chat.chat', compact('viaje', 'mensajes'));
    }

    public function enviarMensaje(Request $request, Viaje $viaje)
    {
        $request->validate([
            'mensaje' => 'required|string'
        ]);

        $userId = Auth::id();

        $otroUsuarioId = $viaje->conductor_id == $userId
            ? $viaje->reservas()->first()->user_id
            : $viaje->conductor_id;

        Mensaje::create([
            'viaje_id' => $viaje->id,
            'emisor_id' => $userId,
            'receptor_id' => $otroUsuarioId,
            'mensaje' => $request->mensaje,
        ]);

        return back();
    }
}
