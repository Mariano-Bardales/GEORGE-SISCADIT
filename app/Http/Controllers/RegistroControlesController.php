<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistroControlesController extends Controller
{
    /**
     * Mostrar página de registro de controles
     */
    public function index()
    {
        return view('dashboard.registro-controles');
    }
}


