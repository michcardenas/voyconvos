<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Termino;

class TerminosController extends Controller
{
    public function index()
    {
        $termino = Termino::first();
        return view('terminos.index', compact('termino'));
    }
}

