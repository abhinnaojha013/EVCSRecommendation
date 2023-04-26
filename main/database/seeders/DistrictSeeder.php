<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $districts = [
            ["Bhojpur","1"],
            ["Dhankuta","1"],
            ["Ilam","1"],
            ["Jhapa","1"],
            ["Khotang","1"],
            ["Morang","1"],
            ["Okhaldhunga","1"],
            ["Panchthar","1"],
            ["Sankhuwasabha","1"],
            ["Solukhumbu","1"],
            ["Sunsari","1"],
            ["Taplejung","1"],
            ["Terhathum","1"],
            ["Udayapur","1"],
            ["Bara","2"],
            ["Dhanusha","2"],
            ["Mahottari","2"],
            ["Parsa","2"],
            ["Rautahat","2"],
            ["Saptari","2"],
            ["Sarlahi","2"],
            ["Siraha","2"],
            ["Bhaktapur","3"],
            ["Chitwan","3"],
            ["Dhading","3"],
            ["Dolakha","3"],
            ["Kathmandu","3"],
            ["Kavrepalanchok","3"],
            ["Lalitpur","3"],
            ["Makwanpur","3"],
            ["Nuwakot","3"],
            ["Ramechhap","3"],
            ["Rasuwa","3"],
            ["Sindhuli","3"],
            ["Sindhupalchok","3"],
            ["Baglung","4"],
            ["Gorkha","4"],
            ["Kaski","4"],
            ["Lamjung","4"],
            ["Manang","4"],
            ["Mustang","4"],
            ["Myagdi","4"],
            ["Nawalpur","4"],
            ["Parbat","4"],
            ["Syangja","4"],
            ["Tanahun","4"],
            ["Arghakhanchi","5"],
            ["Banke","5"],
            ["Bardiya","5"],
            ["Dang Deukhuri","5"],
            ["Eastern Rukum","5"],
            ["Gulmi","5"],
            ["Kapilvastu","5"],
            ["Parasi","5"],
            ["Palpa","5"],
            ["Pyuthan","5"],
            ["Rolpa","5"],
            ["Rupandehi","5"],
            ["Dailekh","6"],
            ["Dolpa","6"],
            ["Humla","6"],
            ["Jajarkot","6"],
            ["Jumla","6"],
            ["Kalikot","6"],
            ["Mugu","6"],
            ["Salyan","6"],
            ["Surkhet","6"],
            ["Western Rukum","6"],
            ["Achham","7"],
            ["Baitadi","7"],
            ["Bajhang","7"],
            ["Bajura","7"],
            ["Dadeldhura","7"],
            ["Darchula","7"],
            ["Doti","7"],
            ["Kailali","7"],
            ["Kanchanpur","7"]
        ];



        foreach ($districts as $district) {
            DB::table('districts')->insert([
                'district_name' => $district[0],
                'province' => $district[1],
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

    }
}
