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
                $chargingStationModel = new ChargingStation();
                $data['charging_stations'] = $chargingStationModel->selectIndex();
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

    public function store (Request $request) {
        try {
            $locationsModel = new Locations();
            $location_existing = $locationsModel->selectLocations($request);

            if ($location_existing->isEmpty()) {
                $location_id = $locationsModel->insertLocation($request);
            } else {
                $location_id = $location_existing[0]->id;
            }

            $chargingStationModel =  new ChargingStation();
            $chargingStation = $chargingStationModel->insertChargingStation($request, $location_id);

            $this->calculateSimilarityScores($chargingStation);

            request()->session()->flash('success', 'Charging station creation success');
            return redirect()->route('chargingStation.index');
        } catch (Exception $exception) {
            request()->session()->flash('error', 'Charging station creation failed');
            return redirect()->route('chargingStation.create');
        }
    }

    function getChargingStations(Request $request) {
        $chargingStationModel = new ChargingStation();
        $chargingStations = $chargingStationModel->selectChargingStations($request);
        return response()->json($chargingStations, 200);
    }

    function calculateSimilarityScores($id) {
        $chargingStationModel = new ChargingStation();
        $similarityScoresModel = new SimilarityScores();

        $referenceChargingStation = $chargingStationModel->selectReferenceChargingStation($id);
        $chargingStations = $chargingStationModel->selectBelowId($id);

        $nearest_restaurant_reference = $this->distance_scale($referenceChargingStation[0]->nearest_restaurant);
        $nearest_shopping_mall_reference = $this->distance_scale($referenceChargingStation[0]->nearest_shopping_mall);
        $nearest_cinema_hall_reference = $this->distance_scale($referenceChargingStation[0]->nearest_cinema_hall);

        $bSquared = $referenceChargingStation[0]->ac_ports_fast * $referenceChargingStation[0]->ac_ports_fast +
            $referenceChargingStation[0]->dc_ports_fast * $referenceChargingStation[0]->dc_ports_fast +
            $referenceChargingStation[0]->ac_ports_regular * $referenceChargingStation[0]->ac_ports_regular +
            $referenceChargingStation[0]->dc_ports_regular * $referenceChargingStation[0]->dc_ports_regular +
            $nearest_restaurant_reference * $nearest_restaurant_reference +
            $nearest_shopping_mall_reference * $nearest_shopping_mall_reference +
            $nearest_cinema_hall_reference * $nearest_cinema_hall_reference;

            foreach ($chargingStations as $chargingStation) {
                $nearest_restaurant = $this->distance_scale($chargingStation->nearest_restaurant);
                $nearest_shopping_mall = $this->distance_scale($chargingStation->nearest_shopping_mall);
                $nearest_cinema_hall = $this->distance_scale($chargingStation->nearest_cinema_hall);

                $ab = $chargingStation->ac_ports_fast * $referenceChargingStation[0]->ac_ports_fast +
                    $chargingStation->dc_ports_fast * $referenceChargingStation[0]->dc_ports_fast +
                    $chargingStation->ac_ports_regular * $referenceChargingStation[0]->ac_ports_regular +
                    $chargingStation->dc_ports_regular * $referenceChargingStation[0]->dc_ports_regular +
                    $nearest_restaurant * $nearest_restaurant_reference +
                    $nearest_shopping_mall * $nearest_shopping_mall_reference +
                    $nearest_cinema_hall * $nearest_cinema_hall_reference;

                $aSquared = $chargingStation->ac_ports_fast * $chargingStation->ac_ports_fast +
                    $chargingStation->dc_ports_fast * $chargingStation->dc_ports_fast +
                    $chargingStation->ac_ports_regular * $chargingStation->ac_ports_regular +
                    $chargingStation->dc_ports_regular * $chargingStation->dc_ports_regular +
                    $nearest_restaurant * $nearest_restaurant +
                    $nearest_shopping_mall * $nearest_shopping_mall +
                    $nearest_cinema_hall * $nearest_cinema_hall;

                $similarityScore = $ab / (sqrt($aSquared) * sqrt($bSquared));

                $similarityScoresModel->insertSimilarityScore($referenceChargingStation[0]->id, $chargingStation->id, $similarityScore);
        }
    }

    function distance_scale($distance_str) {
        $distance =  (float)$distance_str;
        if ($distance == 0) {
            return 0;
        }
        else if($distance <= 50) {
            return 10;
        } else if($distance <= 100) {
            return 9;
        } else if($distance <= 150) {
            return 8;
        } else if($distance <= 200) {
            return 7;
        } else if($distance <= 250) {
            return 6;
        } else if($distance <= 300) {
            return 5;
        } else if($distance <= 350) {
            return 4;
        } else if($distance <= 400) {
            return 3;
        } else if($distance <= 450) {
            return 2;
        } else if($distance <= 500) {
            return 1;
        } else {
            return 0;
        }
    }
}
