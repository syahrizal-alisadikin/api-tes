<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    } 
    public function index(Request $request)
    {
        $article = Article::offset($request->offset)->limit($request->limit)->get();
        return response()->json($article);
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }
}
