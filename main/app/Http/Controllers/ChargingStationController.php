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

            foreach ($chargingStations as $chargingStation) {
                $ab = $chargingStation->ac_ports_fast * $referenceChargingStation[0]->ac_ports_fast +
                    $chargingStation->dc_ports_fast * $referenceChargingStation[0]->dc_ports_fast +
                    $chargingStation->ac_ports_regular * $referenceChargingStation[0]->ac_ports_regular +
                    $chargingStation->dc_ports_regular * $referenceChargingStation[0]->dc_ports_regular;

                $aSquared = $chargingStation->ac_ports_fast * $chargingStation->ac_ports_fast +
                    $chargingStation->dc_ports_fast * $chargingStation->dc_ports_fast +
                    $chargingStation->ac_ports_regular * $chargingStation->ac_ports_regular +
                    $chargingStation->dc_ports_regular * $chargingStation->dc_ports_regular;

                $bSquared = $referenceChargingStation[0]->ac_ports_fast * $referenceChargingStation[0]->ac_ports_fast +
                    $referenceChargingStation[0]->dc_ports_fast * $referenceChargingStation[0]->dc_ports_fast +
                    $referenceChargingStation[0]->ac_ports_regular * $referenceChargingStation[0]->ac_ports_regular +
                    $referenceChargingStation[0]->dc_ports_regular * $referenceChargingStation[0]->dc_ports_regular;

                $similarityScore = $ab / (sqrt($aSquared) * sqrt($bSquared));

                $similarityScoresModel->insertSimilarityScore($referenceChargingStation[0]->id, $chargingStation->id, $similarityScore);
        }
    }
}
