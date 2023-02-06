<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Districts extends Model
{
    use HasFactory;

    function selectDistrict($request) {
        return DB::table('districts')
            ->select('id','district_name')
            ->where('province','=', $request->get('province'))
            ->get();
    }
}
