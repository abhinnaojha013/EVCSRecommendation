<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChargingStation extends Model
{
    use HasFactory;

    function selectIndex() {
        return DB::table('charging_stations')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->join('metropolitans', 'locations.metropolitan', '=', 'metropolitans.id')
            ->join('districts', 'metropolitans.district', '=', 'districts.id')
            ->join('provinces', 'districts.province', '=', 'provinces.id')
            ->select('charging_stations.id as cs_id',
                'charging_stations.charging_station_name as cs_name',
                'locations.ward_number as ward_number',
                'metropolitans.metropolitan_name as metropolitan',
                'districts.district_name as district',
                'provinces.province_name as province'
            )->get();
    }

    function insertChargingStation($request, $location_id) {
        $now = Carbon::now();
        return DB::table('charging_stations')->insertGetId([
            'charging_station_name' => $request->get('charging_station_name'),
            'location' => $location_id,
            'ac_ports_fast' => $request->get('ac_ports_fast'),
            'dc_ports_fast' => $request->get('dc_ports_fast'),
            'ac_ports_regular' => $request->get('ac_ports_regular'),
            'dc_ports_regular' => $request->get('dc_ports_regular'),
            'nearest_restaurant' => $request->get('nearest_restaurant'),
            'nearest_shopping_mall' => $request->get('nearest_shopping_mall'),
            'nearest_cinema_hall' => $request->get('nearest_cinema_hall'),
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    function selectChargingStations($request) {
        return DB::table('charging_stations')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->select('charging_stations.id as cs_id',
                'charging_stations.charging_station_name as cs_name'
            )
            ->where('locations.metropolitan', '=', $request->get('metropolitan'))
            ->where('locations.ward_number', '=', $request->get('ward_number'))
            ->get();
    }

    function selectBelowId($id) {
        return DB::table('charging_stations')
            ->select(
                'availability',
                'charging_station_name',
                'location',
                'ac_ports_fast',
                'dc_ports_fast',
                'ac_ports_regular',
                'dc_ports_regular',
                'nearest_restaurant',
                'nearest_shopping_mall',
                'nearest_cinema_hall'
            )
            ->where('id', '<', $id)
            ->get();
    }

    function selectReferenceChargingStation($id) {
        return DB::table('charging_stations')
            ->select(
                'availability',
                'charging_station_name',
                'location',
                'ac_ports_fast',
                'dc_ports_fast',
                'ac_ports_regular',
                'dc_ports_regular',
                'nearest_restaurant',
                'nearest_shopping_mall',
                'nearest_cinema_hall'
            )
            ->where('id', '=', $id)
            ->get();
    }
}
