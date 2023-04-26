<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChargingStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $chargingStations = [
            ['Birtamod', '10', '4'],
        ];
        foreach ($chargingStations as $chargingStation) {
            DB::table('$charging_stations')->insert([
                'charging_station_name' => $chargingStation[0],
                'location' => $chargingStation[0],
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
