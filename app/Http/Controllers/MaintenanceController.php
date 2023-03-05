<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function maintenance()
    {
        return view('errors.maintenance');
    }

    public function notpeople()
    {
        return view('errors.notpeople');
    }

    public function error()
    {
        return view('errors.error');
    }
}
