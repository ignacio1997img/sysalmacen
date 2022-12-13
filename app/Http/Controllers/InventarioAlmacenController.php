<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventarioAlmacenController extends Controller
{
    public function index()
    {
        return view('almacenes.inventario.browse');
    }
}
