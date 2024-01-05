<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function toastrNot()
    {
        session()->flash('success', 'Operation completed successfully!');

        return back()->with('success', 'working');
    }
}
