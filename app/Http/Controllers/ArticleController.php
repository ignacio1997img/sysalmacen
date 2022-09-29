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
      
        $query_filter = 'sucursal_id = '.$sucursal->sucursal_id;
        if (Auth::user()->hasRole('admin')) {
            $query_filter = 1;
        }

        $article = Article::whereRaw($query_filter)->get();
        // return $article;

        return view('almacenes.article.browse', compact('article'));
    }
}
