<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('users')->insert([
            'name' => 'abhinna',
            'email' => 'abhinna@oic.com',
            'password' => bcrypt('111111111')
        ]);

        DB::table('users')->insert([
            'name' => 'rajat',
            'email' => 'rajat@oic.com',
            'password' => bcrypt('111111111')
        ]);

        DB::table('users')->insert([
            'name' => 'bipin',
            'email' => 'bipin@oic.com',
            'password' => bcrypt('111111111')
        ]);
    }
}
