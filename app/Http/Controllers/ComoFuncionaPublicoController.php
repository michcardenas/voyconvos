<?php

namespace App\Http\Controllers;

use App\Models\ComoFunciona;

class ComoFuncionaPublicoController extends Controller
{
    public function index()
    {
        $info = ComoFunciona::latest()->firstOrFail();
        return view('como-funciona', compact('info'));
    }
}
