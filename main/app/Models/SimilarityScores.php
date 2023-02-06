<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SimilarityScores extends Model
{
    use HasFactory;

    function insertSimilarityScore($chargingStation1, $chargingStation2, $similarityScore) {
        $now = Carbon::now();

        DB::table('similarity_scores')->insert([
            'charging_station_1' => $chargingStation1,
            'charging_station_2' => $chargingStation2,
            'similarity_score' => $similarityScore,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
