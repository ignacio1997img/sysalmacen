<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SucursalUser;

class ArticleController extends Controller
{
    public function index()
    {
        $sucursal = SucursalUser::where('user_id', Auth::user()->id)->first();
      
        // $query_filter = 'sucursal_id = '.$sucursal->sucursal_id;
        // if (Auth::user()->hasRole('admin')) {
        //     $query_filter = 1;
        // }

        // $article = Article::with(['partida'])
        //     ->whereRaw($query_filter)->get();
        // return $article;

        return view('almacenes.article.browse');
    }

    public function list($search = null){
        $user = Auth::user();
        $paginate = request('paginate') ?? 10;
 
        $data = Article::with(['sucursal', 'partida'])
                ->where(function($query) use ($search){
                    if($search){
                        $query->OrwhereHas('partida', function($query) use($search){
                            $query->whereRaw("(nombre like '%$search%' or nombre like '%$search%')");
                        })
                        ->OrWhereRaw($search ? "nombre like '%$search%'" : 1);
                    }
                })
                ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);

        // dd($data);
        // $data='';





        return view('almacenes.article.list', compact('data'));
    }


    public function getArticle()
    {
        $q = request('q');

        $data = DB::table('articles as a')
            ->join('partidas as p', 'p.id', 'a.partida_id')
            ->where('a.deleted_at', null)
            ->where('a.condicion', 1)
            ->whereRaw($q ? '(a.nombre like "%'.$q.'%" or a.presentacion like "%'.$q.'%")' : 1)
            ->select('a.id', 'a.nombre as article', 'a.presentacion', 'a.image', 'p.nombre as partida', 'p.codigo')
            ->get();

        return response()->json($data);
    }
}
