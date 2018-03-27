<?php

namespace App\Http\Controllers;

use Src\ORM\DB;

class HomeController
{
    public function index()
    {
        $table_1 = DB::table('table_1')->first();
        echo $table_1->column_1;
    }
}
