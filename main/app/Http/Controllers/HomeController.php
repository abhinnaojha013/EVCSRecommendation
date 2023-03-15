<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
