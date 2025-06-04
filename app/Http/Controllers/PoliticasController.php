<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Politica;

class PoliticasController extends Controller
{
    public function index()
    {
        $politica = Politica::latest()->first();
        return view('politicas.index', compact('politica'));
    }
}
