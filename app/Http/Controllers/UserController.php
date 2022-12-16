<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use app\models\User;
use App\Models\SucursalUser;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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





    // para obrtener las personas interna o externas
    public function getFuncionario(Request $request){
        $search = $request->search;
        $type = $request->type;
            if($type==1)
            {
                $personas = DB::connection('mamore')->table('people as p')
                    ->join('contracts as c', 'c.person_id', 'p.id')
                    ->select('p.id', 'p.first_name as nombre', 'p.last_name as apellido', 'p.ci' , DB::raw("CONCAT(p.first_name, ' ', p.last_name) as nombre_completo"))
                    ->where('c.status', 'firmado')
                    ->where('p.deleted_at', null)
                    ->where('c.deleted_at', null)
                    // ->where('p.ci', 'like', '%' .$search . '%')
                    ->whereRaw('(p.ci like "%' .$search . '%" or '.DB::raw("CONCAT(p.first_name, ' ', p.last_name)"). 'like "%' . $search . '%")')
                    ->get();
                    $response = array();
                foreach($personas as $persona){

                    $response[] = array(
                        "id"=>$persona->id,
                        "text"=>$persona->nombre_completo,
                        "nombre" => $persona->nombre,
                        "apellido" => $persona->apellido,
                        // "ap_materno" => $persona->apellido,
                        "ci" => $persona->ci,
                        // "alfanum" => $persona->alfanu,
                        // "departamento_id" => $persona->Expedido
                    );
                }
            }
            else
            {
                $personas = DB::table('sysalmacen.people_exts as s')
                ->join('sysadmin.people as m', 'm.id', '=', 's.people_id')
                ->select(
                    'm.id',
                    DB::raw("CONCAT(m.first_name, ' ', m.last_name) as text"),
                    'm.first_name as nombre', 'm.last_name as apellido',
                    'm.ci',
                )
                ->whereRaw('(m.ci like "%' .$search . '%" or '.DB::raw("CONCAT(m.first_name, ' ', m.last_name)").' like "%' .$search . '%")')
                ->where('s.status',1)
                ->where('s.deleted_at',null)
                ->get();

                $response = array();
                foreach($personas as $persona){

                    $response[] = array(
                        "id"=>$persona->id,
                        "text"=>$persona->text,
                        "nombre" => $persona->nombre,
                        "apellido" => $persona->apellido,
                        "ci" => $persona->ci,
                    );
                }
            }  
        return response()->json($response);
    }


    public function create_user(Request $request){
        
        $ok = User::where('funcionario_id', $request->funcionario_id)->first();
        if($ok)
        {
            return redirect()->route('voyager.users.index')->with(['message' => 'El Funcionario ya cuenta con usuario.', 'alert-type' => 'error']);
        }

        $ok = User::where('email', $request->email)->first();
        if($ok)
        {
            return redirect()->route('voyager.users.index')->with(['message' => 'Elija otro correo por favor.', 'alert-type' => 'error']);
        }
        
        DB::beginTransaction();
        try {
            
            $user = User::create([
                'name' =>  $request->name,
                'funcionario_id' => $request->funcionario_id,
                'role_id' => $request->role_id,
                'email' => $request->email,
                'avatar' => 'users/default.png',
                'password' => bcrypt($request->password),
            ]);
            
            
            
            if ($request->user_belongstomany_role_relationship <> '') {
                $user->roles()->attach($request->user_belongstomany_role_relationship);
            }

            DB::commit();
            return redirect()->route('voyager.users.index')->with(['message' => "El usuario, se registro con exito.", 'alert-type' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('voyager.users.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);

        }     
    }



    public function update_user(Request $request, User $user){
        // return $request;

        $ok = User::where('funcionario_id', $request->funcionario_id)->where('id', '!=', $user->id)->first();
        if($ok)
        {
            return redirect()->route('voyager.users.index')->with(['message' => 'El Funcionario ya cuenta con usuario.', 'alert-type' => 'error']);
        }

        $ok = User::where('email', $request->email)->where('id', '!=', $user->id)->first();
        if($ok)
        {
            return redirect()->route('voyager.users.index')->with(['message' => 'Elija otro correo por favor.', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        try {
            $user->update([
                'role_id' => $request->role_id,
                'email' => $request->email,
            ]);
            
            if ($request->password != '') {
                $user->password = bcrypt($request->password);
                $user->save();
            }
            
            if ($request->funcionario_id != '') {
                $user->update([
                    'funcionario_id' => $request->funcionario_id,
                    'name' => $request->name,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }  

        if ($request->user_belongstomany_role_relationship <> '') {
            $user->roles()->sync($request->user_belongstomany_role_relationship);
        }
        return redirect()
        ->route('voyager.users.index')
        ->with([
                'message' => "El usuario, se actualizo con exito.",
                'alert-type' => 'success'
            ]);
    }
}
