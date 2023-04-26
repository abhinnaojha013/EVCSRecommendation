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
            ['Birtamod', '10', '4'],
            ['Biratnagar', '20', '6'],
            ['Itahari', '20', '11'],
            ['Bardibas', '14', '17'],
            ['Bhaktapur', '12', '23'],
            ['Bharatpur', '29', '24'],
            ['Ratnanagar', '16', '24'],
            ['Kathmandu', '32', '27'],
            ['Kirtipur', '16', '27'],
            ['Hetauda', '19', '30'],
            ['Lalitpur', '22', '29'],
            ['Pokhara', '25', '38'],
            ['Bandipur', '6', '46'],
            ['Nepalgunj', '27', '48'],
            ['Dhangadhi', '19', '76'],
            ['Chisapani', '19', '76'],
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
