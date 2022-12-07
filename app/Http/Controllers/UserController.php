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
        // $ok = SucursalUser::where('sucursal_id', $request->branchoffice_id)->where('user_id', $request->user_id)->where('condicion',1)->first();
        // $ok = SucursalUser::where('sucursal_id', $request->branchoffice_id)->where('condicion',1)->first();
        // if($ok)
        // {
        //     return redirect('admin/users')->with(['message' => 'La sucursal ya se encuentra asignada a una persona.', 'alert-type' => 'error']);
        // }

        $ok = SucursalUser::where('user_id', $request->user_id)->where('condicion',1)->first();
        if($ok)
        {
            return redirect('admin/users')->with(['message' => 'La persona se encuentra asignada a una sucursal activa.', 'alert-type' => 'error']);
        }
        SucursalUser::create(['sucursal_id' => $request->branchoffice_id, 'user_id' => $request->user_id]);
        return redirect()->route('admin/users')->with(['message' => 'Sucursal asignada exitosamente', 'alert-type' => 'success']);

        // return redirect('admin/users/'.$request->user_id.'/edit');
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
