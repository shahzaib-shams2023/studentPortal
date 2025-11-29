<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function getExams($std_id)
    {
        return DB::table('modulars')
            ->where('Std_ID', '=', $std_id)
            ->get();


    }
}
