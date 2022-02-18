<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use app\models\User;
use App\Models\SucursalUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        SucursalUser::create(['sucursal_id' => $request->branchoffice_id, 'user_id' => $request->user_id]);
        return redirect('admin/users/'.$request->user_id.'/edit');
    }


    public function desactivar(Request $request)
    {
        // return $request;
        SucursalUser::where('id',$request->id)->update(['condicion' =>0]);
        return redirect('admin/users/'.$request->user_id.'/edit');
    }
    protected function activar(Request $request)
    {
        // return $request;
        SucursalUser::where('id',$request->id)->update(['condicion' =>1]);
        return redirect('admin/users/'.$request->user_id.'/edit');
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
