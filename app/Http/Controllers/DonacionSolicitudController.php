<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonacionSolicitudController extends Controller
{

    public function index()
    {
        $detalle = DB::table('donacion_ingreso_detalles as d')
                ->join('donacion_articulos as a', 'a.id', 'd.donacionarticulo_id')
                ->join('donacion_categorias as c', 'c.id', 'a.categoria_id')
                ->select('c.nombre as categoria', 'a.nombre', 'a.presentacion')
                ->where('d.deleted_at', null)
                ->where('d.condicion', 1)
                ->groupBy('categoria', 'a.nombre', 'a.presentacion')
                ->get();
// return $detalle;


        return view('solicituddonor.browse', compact('detalle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
