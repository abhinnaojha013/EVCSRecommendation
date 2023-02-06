<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Provinces extends Model
{
    use HasFactory;

    function selectProvinces() {
        return DB::table('provinces')
            ->select('id','province_name')
            ->get();
    }
}
