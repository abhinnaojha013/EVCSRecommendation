<?php

namespace App\Models;

use Carbon\Carbon;
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

    function insertNew($request) {
        $now = Carbon::now();
        return DB::table('metropolitans')->insertGetId([
            'metropolitan_name' => $request->get('metropolitan'),
            'wards' => $request->get('wards'),
            'district' => $request->get('district'),
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
