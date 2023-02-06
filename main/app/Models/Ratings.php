<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ratings extends Model
{
    use HasFactory;

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
}
