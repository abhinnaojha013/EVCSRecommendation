<?php

namespace App\Http\Controllers;

use App\Models\Metropolitans;
use App\Models\Provinces;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetropolitanController extends Controller
{
    public function createMetropolitan () {
        if (Auth::user()) {
            $userModel = new User();

            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 1) {
                $provincesModel = new Provinces();
                $data['provinces'] = $provincesModel->selectProvinces();
                return view('metropolitan.addMetropolitan', compact('data'));
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    public function store (Request $request) {
        if ($request->get('metropolitan') == '') {
            request()->session()->flash('error', 'Please provide metropolitan name');
            return redirect()->route('metropolitan.create');
        } elseif ($request->get('wards') == '') {
            request()->session()->flash('error', 'Please provide number of wards');
            return redirect()->route('metropolitan.create');
        } elseif ($request->get('province') < 1 || $request->get('province') > 7) {
            request()->session()->flash('error', 'Please select a valid province');
            return redirect()->route('metropolitan.create');
        } elseif ($request->get('district') < 1 || $request->get('district') > 77) {
            request()->session()->flash('error', 'Please select a valid district');
            return redirect()->route('metropolitan.create');
        } elseif ($request->get('wards') < 0 || $request->get('wards') > 32) {
            request()->session()->flash('error', 'Please enter a valid number of wards');
            return redirect()->route('metropolitan.create');
        }

        try {
            $metropolitansModel = new Metropolitans();
            $metropolitans = $metropolitansModel->selectMetropolitan($request);
            foreach ($metropolitans as $metros) {
                if ($metros->metropolitan_name == $request->get('metropolitan')) {
                    request()->session()->flash('error', 'Metropolitan already exists');
                    return redirect()->route('metropolitan.create');
                }
            }

            $metropolitansModel->insertNew($request);
            request()->session()->flash('success', 'Metropolitan creation success');
            return redirect()->route('chargingStation.index');
        } catch (\Exception $exception) {
            request()->session()->flash('error', 'Metropolitan creation failed');
            return redirect()->route('metropolitan.create');
        }
    }

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
