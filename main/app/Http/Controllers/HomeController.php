<?php

namespace App\Http\Controllers;

use App\Models\User;
use HIshak\OrsLaravelApi\OpenRouteService;
use HIshak\OrsLaravelApi\Services\OpenRouteDirections;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
//     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()) {
            $userModel = new User();
            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 1) {
                return redirect()->route('chargingStation.index');
            } else if ($user[0]->role == 3){
                return redirect()->route('recommendations.index');
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    public function sandbox() {
        $response = Http::get('http://router.project-osrm.org/route/v1/driving/85.3240,27.7101;85.3484,27.6848?overview=false');
        $response = Http::get('http://router.project-osrm.org/route/v1/driving/1,1;85.3318,27.6974?overview=false');

        echo $response . '<br><br>';

//        echo $response->json('routes')[0]['duration'] . '<br>';
//        echo $response->json('routes')[0]['distance'];

    }
}
