<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $provinces = [
            'Koshi Pradesh',
            'Madhesh Pradesh',
            'Bagmati Pradesh',
            'Gandaki Pradesh',
            'Lumbini Pradesh',
            'Karnali Pradesh',
            'Sudurpaschim Pradesh'
        ];

        foreach ($provinces as $province) {
            DB::table('provinces')->insert([
                'province_name' => $province,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
