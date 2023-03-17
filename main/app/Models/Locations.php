<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Locations extends Model
{
    use HasFactory;

    function selectLocations($request) {
        return DB::table('locations')
            ->select('id')
            ->where('metropolitan', '=', $request->get('metropolitan'))
            ->where('ward_number', '=', $request->get('ward_number'))
            ->get();
    }

    function insertLocation($request) {
        $now = Carbon::now();
        return DB::table('locations')->insertGetId([
            'metropolitan' => $request->get('metropolitan'),
            'ward_number' => $request->get('ward_number'),
            'latitude' => '0',
            'longitude' => '0',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }


}
