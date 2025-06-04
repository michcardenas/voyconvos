<?php

namespace App\Http\Controllers;

use App\Models\SobreNosotros;

class SobreNosotrosPublicoController extends Controller
{
    public function index()
    {
        $info = SobreNosotros::latest()->firstOrFail();
        return view('sobre-nosotros', compact('info'));
    }
}

