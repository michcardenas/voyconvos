<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pagina;


class ConfiguracionController extends Controller
{
    public function index()
    {
        $paginas = Pagina::all();

        return view('admin.configuracion.botones');
    }

}
