<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Metropolitans extends Model
{
    use HasFactory;

    function selectMetropolitan($request) {
        return DB::table('metropolitans')
            ->select('id', 'metropolitan_name')
            ->where('district','=', $request->get('district'))
            ->get();
    }

    function selectMaxWards($request) {
        return DB::table('metropolitans')
            ->select('wards')
            ->where('id','=', $request->get('metropolitan'))
            ->get();
    }
}
