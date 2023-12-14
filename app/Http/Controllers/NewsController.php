<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function createTopic()
    {
        return view('news_creation');
    }

    public function insertNewTopic(Request $request)
    {
        dd($request->all());
    }
}
