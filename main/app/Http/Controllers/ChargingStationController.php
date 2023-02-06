<?php

namespace App\Http\Controllers;

use App\Models\ChargingStation;
use App\Models\Districts;
use App\Models\Locations;
use App\Models\Metropolitans;
use App\Models\Provinces;
use App\Models\SimilarityScores;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Diff\Exception;
use function PHPUnit\Framework\isEmpty;

class ChargingStationController extends Controller
{
    public function index() {
        $chargingStationModel = new ChargingStation();
        $data['charging_stations'] = $chargingStationModel->selectIndex();
        return view('chargingStation.index', compact('data'));
    }

    public function create() {
        $provincesModel = new Provinces();
        $data['provinces'] = $provincesModel->selectProvinces();
        return view('chargingStation.addChargingStation', compact('data'));
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
        } catch (Exception $exception) {

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


        $similarityScore = null;

        foreach ($chargingStations as $chargingStation) {
            $ac_fast = $chargingStation->ac_ports_fast * $referenceChargingStation[0]->ac_ports_fast;
            $dc_fast = $chargingStation->dc_ports_fast * $referenceChargingStation[0]->dc_ports_fast;
            $ac_ports_regular = $chargingStation->ac_ports_regular * $referenceChargingStation[0]->ac_ports_regular;
            $dc_ports_regular = $chargingStation->dc_ports_regular * $referenceChargingStation[0]->dc_ports_regular;


//            $ab = ;
//            $a = ;
//            $b = ;
            $similarityScoresModel->insertSimilarityScore($referenceChargingStation->id, $chargingStation->id, $similarityScore);
        }

    }
}
