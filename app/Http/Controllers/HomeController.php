<?php

namespace App\Http\Controllers;

use Src\ORM\DB;

class HomeController
{
    public static function index()
    {
        DB::table('ops');
        return 'opa';
    }
}
