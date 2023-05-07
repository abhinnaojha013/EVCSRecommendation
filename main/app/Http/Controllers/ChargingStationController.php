<?php

namespace App\Http\Controllers;

use App\Models\ChargingStation;
use App\Models\Districts;
use App\Models\Locations;
use App\Models\Metropolitans;
use App\Models\Provinces;
use App\Models\SimilarityScores;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Diff\Exception;
use function PHPUnit\Framework\isEmpty;

class ChargingStationController extends Controller
{
    public function index() {
        if (Auth::user()) {
            $userModel = new User();

            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 1) {
                $provincesModel = new Provinces();
                $data['provinces'] = $provincesModel->selectProvinces();
                return view('chargingStation.index', compact('data'));
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    public function create() {
        if (Auth::user()) {
            $userModel = new User();

            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 1) {
                $provincesModel = new Provinces();
                $data['provinces'] = $provincesModel->selectProvinces();
                return view('chargingStation.addChargingStation', compact('data'));
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    function getChargingStations(Request $request) {
        $chargingStationModel = new ChargingStation();
        $chargingStations = $chargingStationModel->selectChargingStations($request);
        return response()->json($chargingStations, 200);
    }

    function getChargingStationMetropolitan(Request $request) {
        $chargingStationModel = new ChargingStation();
        $chargingStations = $chargingStationModel->selectIndexMetropolitan($request);
        return response()->json($chargingStations, 200);
    }

    function getChargingStationWard(Request $request) {
        $chargingStationModel = new ChargingStation();
        $chargingStations = $chargingStationModel->selectIndexWard($request);
        return response()->json($chargingStations, 200);
    }

    public function store (Request $request) {
        if ($request->get('charging_station_name') == '') {
            request()->session()->flash('error', 'Please provide charging station name');
            return redirect()->route('chargingStation.create');
        } elseif ($request->get('province') < 1 || $request->get('province') > 7) {
            request()->session()->flash('error', 'Please select a valid province');
            return redirect()->route('chargingStation.create');
        } elseif ($request->get('district') < 1 || $request->get('district') > 77) {
            request()->session()->flash('error', 'Please select a valid district');
            return redirect()->route('chargingStation.create');
        } elseif ($request->get('metropolitan') < 0) {
            request()->session()->flash('error', 'Please select a valid metropolitan');
            return redirect()->route('chargingStation.create');
        } elseif ($request->get('ward_number') < 0 || $request->get('ward_number') > $request->get('max_wards')) {
            request()->session()->flash('error', 'Please enter a valid ward number');
            return redirect()->route('chargingStation.create');
        }

        try {
            $locationsModel = new Locations();
            $location_existing = $locationsModel->selectLocations($request);

            if ($location_existing->isEmpty()) {
                $location_id = $locationsModel->insertLocation($request);
            } else {
                $location_id = $location_existing[0]->id;
            }

            $chargingStationModel =  new ChargingStation();
            $csFlag = $chargingStationModel->oldChargingStationsCreate($location_id, $request);

            if ($csFlag->isNotEmpty()) {
                request()->session()->flash('error', 'Charging station already exists');
                return redirect()->route('chargingStation.create');
            } else {
                $chargingStationModel->insertChargingStation($request, $location_id);
                request()->session()->flash('success', 'Charging station creation success');
                return redirect()->route('chargingStation.index');
            }
        } catch (\Exception $exception) {
            request()->session()->flash('error', 'Charging station creation failed');
            return redirect()->route('chargingStation.create');
        }
    }

    function edit($id) {
        if (Auth::user()) {
            $userModel = new User();

            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 1) {
                $chargingStationModel =  new ChargingStation();
                $provincesModel = new Provinces();
                $data['provinces'] = $provincesModel->selectProvinces();
                $data['charging_station'] = $chargingStationModel->selectEdit($id);
                return view('chargingStation.editChargingStation', compact('data'));
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    function update(Request $request) {
        $csid = $request->get('charging_station_id');
        if ($request->get('charging_station_name') == '') {
            request()->session()->flash('error', 'Please provide charging station name');
            return redirect()->route('chargingStation.edit', $csid);
        } elseif ($request->get('province') < 1 || $request->get('province') > 7) {
            request()->session()->flash('error', 'Please select a valid province');
            return redirect()->route('chargingStation.edit', $csid);
        } elseif ($request->get('district') < 1 || $request->get('district') > 77) {
            request()->session()->flash('error', 'Please select a valid district');
            return redirect()->route('chargingStation.edit', $csid);
        } elseif ($request->get('metropolitan') < 0) {
            request()->session()->flash('error', 'Please select a valid metropolitan');
            return redirect()->route('chargingStation.edit', $csid);
        } elseif ($request->get('ward_number') < 0 || $request->get('ward_number') > $request->get('max_wards')) {
            request()->session()->flash('error', 'Please enter a valid ward number');
            return redirect()->route('chargingStation.edit', $csid);
        }

        try {
            $locationsModel = new Locations();
            $location_existing = $locationsModel->selectLocations($request);

            if ($location_existing->isEmpty()) {
                $location_id = $locationsModel->insertLocation($request);
            } else {
                $location_id = $location_existing[0]->id;
            }

            $chargingStationModel =  new ChargingStation();
            $csFlag = $chargingStationModel->oldChargingStationsUpdate($location_id, $request);

            if ($csFlag->isNotEmpty()) {
                request()->session()->flash('error', 'Charging station already exists');
                return redirect()->route('chargingStation.edit', $csid);
            } else {
                $chargingStationModel->updateChargingStation($request, $location_id);
                request()->session()->flash('success', 'Charging station update success');
                return redirect()->route('chargingStation.index');
            }
        } catch (\Exception $exception) {
            request()->session()->flash('error', 'Charging station update failed');
            return redirect()->route('chargingStation.edit', $csid);
        }
    }
}
