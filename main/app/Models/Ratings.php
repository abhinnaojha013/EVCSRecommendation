<?php

namespace App\Models;

use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ratings extends Model
{
    use HasFactory;

    function oldUserRating($request) {
        return DB::table('ratings')
            ->where('user', '=', Auth::id())
            ->where('charging_station', '=', $request->charging_station)
            ->get();
    }

    function getUserChargingStationRating($id) {
        return DB::table('ratings')
            ->join('charging_stations', 'ratings.charging_station', '=', 'charging_stations.id')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->join('metropolitans', 'locations.metropolitan', '=', 'metropolitans.id')
            ->join('districts', 'metropolitans.district', '=', 'districts.id')
            ->join('provinces', 'districts.province', '=', 'provinces.id')
            ->select('ratings.id as r_id',
                'charging_stations.charging_station_name as cs_name',
                'ratings.rating as rating',
                'locations.ward_number as ward_number',
                'metropolitans.metropolitan_name as metropolitan',
                'districts.district_name as district',
                'provinces.province_name as province'
            )->where('ratings.user', '=', $id)
            ->get();
    }

    function insertRating($request) {
        $now = Carbon::now();

        DB::table('ratings')->insert([
            'user' => Auth::id(),
            'charging_station' => $request->get('charging_station'),
            'rating' => $request->get('rating'),
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    function userRatings() {
        return DB::table('ratings')
            ->select('id',
                'charging_station',
                'rating'
            )->where('ratings.user', '=', Auth::id())
            ->get();
    }
}
