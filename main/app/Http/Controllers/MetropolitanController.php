<?php

namespace App\Http\Controllers;

use App\Models\Metropolitans;
use Illuminate\Http\Request;

class MetropolitanController extends Controller
{
    public function getMetropolitans (Request $request) {
        $metropolitansModel = new Metropolitans();
        $metropolitans = $metropolitansModel->selectMetropolitan($request);
        return response()->json($metropolitans, 200);
    }

    public function getWards (Request $request) {
        $metropolitansModel = new Metropolitans();
        $metropolitans = $metropolitansModel->selectMaxWards($request);
        return response()->json($metropolitans, 200);
    }
}
