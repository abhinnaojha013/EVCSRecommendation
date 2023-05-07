<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChargingStation extends Model
{
    use HasFactory;


    function selectIndexMetropolitan($request) {
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
            )->where('locations.metropolitan', '=', $request->get('metropolitan'))
            ->get();
    }

    function selectIndexWard($request) {
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
            )->where('locations.metropolitan', '=', $request->get('metropolitan'))
            ->where('locations.ward_number', '=', $request->get('ward_number'))
            ->get();
    }

    function selectEdit($id) {
        return DB::table('charging_stations')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->join('metropolitans', 'locations.metropolitan', '=', 'metropolitans.id')
            ->join('districts', 'metropolitans.district', '=', 'districts.id')
            ->join('provinces', 'districts.province', '=', 'provinces.id')
            ->select('charging_stations.id as cs_id',
                'charging_stations.charging_station_name as cs_name',
                'locations.ward_number as ward_number',
                'metropolitans.id as metropolitan',
                'districts.id as district',
                'provinces.id as province',
                'charging_stations.ac_ports_fast as ac_fast',
                'charging_stations.dc_ports_fast as dc_fast',
                'charging_stations.ac_ports_regular as ac_reg' ,
                'charging_stations.dc_ports_regular as dc_reg' ,
                'charging_stations.nearest_restaurant as restaurant',
                'charging_stations.nearest_shopping_mall as mall',
                'charging_stations.nearest_cinema_hall as cinema',
            )->where('charging_stations.id', '=', $id)
            ->get();
    }

    function insertChargingStation($request, $location_id) {
        $now = Carbon::now();
        return DB::table('charging_stations')->insert([
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

    function updateChargingStation($request, $location_id) {
        $now = Carbon::now();
        DB::table('charging_stations')
            ->where('id', '=', $request->get('charging_station_id'))
            ->update([
                'charging_station_name' => $request->get('charging_station_name'),
                'location' => $location_id,
                'ac_ports_fast' => $request->get('ac_ports_fast'),
                'dc_ports_fast' => $request->get('dc_ports_fast'),
                'ac_ports_regular' => $request->get('ac_ports_regular'),
                'dc_ports_regular' => $request->get('dc_ports_regular'),
                'nearest_restaurant' => $request->get('nearest_restaurant'),
                'nearest_shopping_mall' => $request->get('nearest_shopping_mall'),
                'nearest_cinema_hall' => $request->get('nearest_cinema_hall'),
                'updated_at' => $now,
            ]);
    }

    function getChargingStationAttributes($id) {
        return DB::table('charging_stations')
            ->select(
                'charging_station_name',
                'ac_ports_fast',
                'dc_ports_fast',
                'ac_ports_regular',
                'dc_ports_regular',
                'nearest_restaurant',
                'nearest_shopping_mall',
                'nearest_cinema_hall',
                'latitude',
                'longitude',
            )->where('id', '=', $id)
            ->get();
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

    function getChargingStationWard($request) {
        return DB::table('charging_stations')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->select('charging_stations.id as charging_station',
            )->where('locations.metropolitan', '=', $request->metropolitan)
            ->where('locations.ward_number', '=', $request->ward_number)
            ->get();
    }

    function getChargingStationNoWard($request) {
        return DB::table('charging_stations')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->select('charging_stations.id as charging_station',
            )->where('locations.metropolitan', '=', $request->metropolitan)
            ->get();
    }

    function getFinalRecommendation($csid1) {
        return DB::table('charging_stations')
            ->join('locations','charging_stations.location','=', 'locations.id')
            ->join('metropolitans', 'locations.metropolitan', '=', 'metropolitans.id')
            ->join('districts', 'metropolitans.district', '=', 'districts.id')
            ->join('provinces', 'districts.province', '=', 'provinces.id')
            ->select('charging_stations.id as cs_id',
                'charging_stations.charging_station_name as cs_name',
                'charging_stations.latitude as latitude',
                'charging_stations.longitude as longitude',
                'locations.ward_number as ward_number',
                'metropolitans.metropolitan_name as metropolitan',
                'districts.district_name as district',
                'provinces.province_name as province'
            )->where('charging_stations.id', '=', $csid1)
            ->get();
    }

    function oldChargingStationsCreate($id, $request) {
        return DB::table('charging_stations')
            ->select('charging_station_name'
            )->where('location', '=', $id)
            ->where('charging_station_name', '=', $request->get('charging_station_name'))
            ->get();
    }

    function oldChargingStationsUpdate($id, $request) {
        return DB::table('charging_stations')
            ->select('charging_station_name'
            )->where('location', '=', $id)
            ->where('charging_station_name', '=', $request->get('charging_station_name'))
            ->where('id', '!=', $request->get('charging_station_id'))
            ->get();
    }
}
