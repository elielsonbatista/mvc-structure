<?php

namespace App\Http\Controllers;

use Src\Database\QueryBuilder;

class HomeController
{
    public function index()
    {
        $table_1 = QueryBuilder::table('table_1')->first();
        
        return $table_1->column_1;
    }
}
