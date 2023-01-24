<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class ChargingStationController extends Controller
{
    public function create() {
        $data['provinces'] = DB::table('provinces')
            ->select('id','province_name')
            ->get();
        return view('chargingStation.addChargingStation', compact('data'));
    }

    public function store (Request $request) {
        $location_id = null;
        $location_existing = DB::table('locations')
            ->select('id')
            ->where('metropolitan', '=', $request->get('metropolitan'))
            ->where('ward_number', '=', $request->get('ward_number'))
            ->get();


        $now = Carbon::now();
        if ($location_existing->isEmpty()) {
            $location_id = DB::table('locations')->insertGetId([
                'metropolitan' => $request->get('metropolitan'),
                'ward_number' => $request->get('ward_number'),
                'created_at' => $now,
                'updated_at' => $now
            ]);
        } else {
            $location_id = $location_existing[0]->id;
        }

        $now = Carbon::now();
        DB::table('charging_stations')->insert([
            'charging_station_name' => $request->get('charging_station_name'),
            'location' => $location_id,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }

    public function getDistricts (Request $request) {
        $districts = DB::table('districts')
            ->select('id','district_name')
            ->where('province','=', $request->get('province'))
            ->get();

        return response()->json($districts, 200);
    }

    public function getMetropolitans (Request $request) {
        $metropolitans = DB::table('metropolitans')
            ->select('id', 'metropolitan_name')
            ->where('district','=', $request->get('district'))
            ->get();

        return response()->json($metropolitans, 200);
    }

    public function getWards (Request $request) {
        $metropolitans = DB::table('metropolitans')
            ->select('wards')
            ->where('id','=', $request->get('metropolitan'))
            ->get();

        return response()->json($metropolitans, 200);
    }
}
