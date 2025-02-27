<?php

namespace App\Models\College;
use DB;

use Illuminate\Database\Eloquent\Model;

class Colleges extends Model
{
    public function index()
    {
        return Article::all();
    }

    public function show(Article $article)
    {
        return $article;
    }

    public function store(Request $request)
    {
        
    }

    public function update(Request $request, Article $article)
    {
       
    }

    public function delete(Article $article)
    {
      
    }

}
