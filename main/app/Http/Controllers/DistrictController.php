<?php

namespace App\Http\Controllers;

use App\Models\Districts;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function getDistricts (Request $request) {
        $districtsModel = new Districts();
        $districts = $districtsModel->selectDistrict($request);
        return response()->json($districts, 200);
    }
}
