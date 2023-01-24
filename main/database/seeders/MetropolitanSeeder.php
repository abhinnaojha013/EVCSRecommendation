<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetropolitanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $metropolitans = [
            ['Biratnagar', '20', '6'],
            ['Kathmandu', '32', '27'],
            ['Kirtipur', '16', '27'],
            ['Bhaktapur', '12', '23'],
            ['Lalitpur', '22', '29'],
            ['Pokhara', '25', '38'],
            ['Nepalgunj', '27', '48'],
        ];

        foreach ($metropolitans as $metropolitan) {
            DB::table('metropolitans')->insert([
                'metropolitan_name' => $metropolitan[0],
                'wards' => $metropolitan[1],
                'district' => $metropolitan[2],
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}
