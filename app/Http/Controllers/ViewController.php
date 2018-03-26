<?php

namespace App\Http\Controllers;

use Src\View\View;

class ViewController
{
    public function index()
    {
        return view('index.html')->render();
    }
}
