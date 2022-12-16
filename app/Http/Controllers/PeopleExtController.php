<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Person;
use App\Models\Direction;
use App\Models\PeopleExt;

class PeopleExtController extends Controller
{
    public function index()
    {
        // return Person::all();
        return view('almacenes.peopleExt.browse');
    }

    public function list($search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;
 
        $data = PeopleExt::with(['people', 'direction'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('people', function($query) use($search){
                            $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                        });
                    }
                })
                ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);

        // dd($data);





        return view('almacenes.peopleExt.list', compact('data'));
    }

    public function create()
    {
        $direction = Direction::where('estado', 1)->where('deleted_at',null)->get();
        $people = Person::where('deleted_at',null)->get();
        // return $direction;
        return view('almacenes.peopleExt.add', compact('direction', 'people'));

    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            PeopleExt::create([
                'people_id' => $request->people_id,
                'direccionAdministrativa_id' => $request->direccionAdministrativa_id,
                'cargo' => $request->cargo,
                'start' => $request->start,
                'finish' => $request->finish,
                'registerUser_id'=> Auth::user()->id
            ]);

            DB::commit();
            return redirect()->route('people_ext.index')->with(['message' => 'Registrado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('people_ext.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }
    }

    public function destroy($people_ext)
    {
        DB::beginTransaction();
        try {
            PeopleExt::where('id', $people_ext)->update(['deleted_at'=>Carbon::now()]);

            DB::commit();
            return redirect()->route('people_ext.index')->with(['message' => 'Eliminado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('people_ext.index')->with(['message' => 'Ocurrio un error.', 'alert-type' => 'error']);
        }

    }
}
