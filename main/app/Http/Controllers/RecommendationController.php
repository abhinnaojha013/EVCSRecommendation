<?php

namespace App\Http\Controllers;

use App\Models\ChargingStation;
use App\Models\Locations;
use App\Models\Provinces;
use App\Models\Ratings;
use App\Models\SimilarityScores;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\isEmpty;

class RecommendationController extends Controller
{
    function index() {
        if (Auth::user()) {
            $userModel = new User();
            $provinceModel = new Provinces();

            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 3) {
                $data['provinces'] = $provinceModel->selectProvinces();
                $data['user'] = Auth::id();
                $data['recommendations'] = [];
                return view('recommend.recommend', compact('data'));
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    function getRecommendation(Request $request) {
        $ratingsModel = new Ratings();
        $chargingStationModel = new ChargingStation();
        $similarityScoreModel = new SimilarityScores();

        $recommendationRating = [0, 0, 0];
        $recommendations = [0, 0, 0];

        $user_rating = $ratingsModel->userRatings();
        $chargingStationLocation = '';
        if($user_rating->count() < 3) {
            request()->session()->flash('error', 'Please rate at least 3 charging stations.');
            return redirect()->route('recommendations.index');
        } else {
            if($request->get('ward_enabled') == 0) {
                $chargingStationLocation = $chargingStationModel->getChargingStationNoWard($request);
            } elseif ($request->get('ward_enabled') == 1) {
                $chargingStationLocation = $chargingStationModel->getChargingStationWard($request);
            }

            foreach ($chargingStationLocation as $csl) {
                $ratingEstimateNum = 0;
                $ratingEstimateDen = 0;
                foreach ($user_rating as $ur) {
                    $similarityScore = $this->calculateSimilarityScores($ur->charging_station, $csl->charging_station);
                    $ratingEstimateNum = $ratingEstimateNum + $ur->rating * $similarityScore;
                    $ratingEstimateDen += $similarityScore;
                }
                if ($ratingEstimateDen == 0) {
                    $ratingEstimate = 0;
                } else {
                    $ratingEstimate = $ratingEstimateNum / $ratingEstimateDen;
                }
                if ($ratingEstimate >= $recommendationRating[0]) {
                    $recommendationRating[2] = $recommendationRating[1];
                    $recommendationRating[1] = $recommendationRating[0];
                    $recommendationRating[0] = $ratingEstimate;

                    $recommendations[2] = $recommendations[1];
                    $recommendations[1] = $recommendations[0];
                    $recommendations[0] = $csl->charging_station;
                } elseif ($ratingEstimate >= $recommendationRating[1]) {
                    $recommendationRating[2] = $recommendationRating[1];
                    $recommendationRating[1] = $ratingEstimate;

                    $recommendations[2] = $recommendations[1];
                    $recommendations[1] = $csl->charging_station;
                } elseif ($ratingEstimate >= $recommendationRating[2]) {
                    $recommendationRating[2] = $ratingEstimate;

                    $recommendations[2] = $csl->charging_station;
                }
            }
        }
        $provinceModel = new Provinces();
        $data['provinces'] = $provinceModel->selectProvinces();
        $data['user'] = Auth::id();

        $c_lat = $request->get('latitude');
        $c_long = $request->get('longitude');

//        $data['recommendations'] = $chargingStationModel->getFinalRecommendations($recommendations[0], $recommendations[1], $recommendations[2]);
        $recommendation1 = $chargingStationModel->getFinalRecommendation($recommendations[0]);
        $recommendation2 = $chargingStationModel->getFinalRecommendation($recommendations[1]);
        $recommendation3 = $chargingStationModel->getFinalRecommendation($recommendations[2]);

        $cs_dist_1 = '';
        $cs_dist_2 = '';
        $cs_dist_3 = '';

        $cs_dur_1 = '';
        $cs_dur_2 = '';
        $cs_dur_3 = '';

        if($recommendation1->count() > 0) {
            $cs_url_1 = 'http://router.project-osrm.org/route/v1/driving/' . $recommendation1[0]->longitude . ',' . $recommendation1[0]->latitude . ';' . $c_long . ','. $c_lat.'?overview=false';
            $cs_response_1 = Http::get($cs_url_1);
            $cs_dist_1 = (double) $cs_response_1->json('routes')[0]['distance'];
            $cs_dist_1 = $cs_dist_1 / 1000;
            $cs_dur_1 = (double) $cs_response_1->json('routes')[0]['duration'];
            $cs_dur_1 = $cs_dur_1 / 60;
        }

        if($recommendation2->count() > 0) {
            $cs_url_2 = 'http://router.project-osrm.org/route/v1/driving/' . $recommendation2[0]->longitude . ',' . $recommendation2[0]->latitude . ';' . $c_long . ','. $c_lat.'?overview=false';
            $cs_response_2 = Http::get($cs_url_2);
            $cs_dist_2 = (double) $cs_response_2->json('routes')[0]['distance'];
            $cs_dist_2 = $cs_dist_2 / 1000;
            $cs_dur_2 = (double) $cs_response_2->json('routes')[0]['duration'];
            $cs_dur_2 = $cs_dur_2 / 60;
        }
        if($recommendation3->count() > 0) {
            $cs_url_3 = 'http://router.project-osrm.org/route/v1/driving/' . $recommendation3[0]->longitude . ',' . $recommendation3[0]->latitude . ';' . $c_long . ','. $c_lat.'?overview=false';
            $cs_response_3 = Http::get($cs_url_3);
            $cs_dist_3 = (double) $cs_response_3->json('routes')[0]['distance'];
            $cs_dist_3 = $cs_dist_3 / 1000;
            $cs_dur_3 = (double) $cs_response_3->json('routes')[0]['duration'];
            $cs_dur_3 = $cs_dur_3 / 60;
        }

        if(abs($recommendationRating[0] - $recommendationRating[1]) <= 0.25) {
            if($cs_dur_2 < $cs_dur_1) {
                $t = $cs_dur_2;
                $cs_dur_2 = $cs_dur_1;
                $cs_dur_1 = $t;

                $t = $cs_dist_2;
                $cs_dist_2 = $cs_dist_1;
                $cs_dist_1 = $t;

                $t = $recommendationRating[1];
                $recommendationRating[1] = $recommendationRating[0];
                $recommendationRating[0] = $t;

                $t = $recommendation2;
                $recommendation2 = $recommendation1;
                $recommendation1 = $t;
            }
        }

        if(abs($recommendationRating[0] - $recommendationRating[2]) <= 0.25) {
            if($cs_dur_3 < $cs_dur_1) {
                $t = $cs_dur_3;
                $cs_dur_3 = $cs_dur_1;
                $cs_dur_1 = $t;

                $t = $cs_dist_3;
                $cs_dist_3 = $cs_dist_1;
                $cs_dist_1 = $t;

                $t = $recommendationRating[2];
                $recommendationRating[2] = $recommendationRating[0];
                $recommendationRating[0] = $t;

                $t = $recommendation3;
                $recommendation3 = $recommendation1;
                $recommendation1 = $t;
            }
        }

        if(abs($recommendationRating[1] - $recommendationRating[2]) <= 0.25) {
            if($cs_dur_3 < $cs_dur_2) {
                $t = $cs_dur_3;
                $cs_dur_3 = $cs_dur_2;
                $cs_dur_2 = $t;

                $t = $cs_dist_3;
                $cs_dist_3 = $cs_dist_2;
                $cs_dist_2 = $t;

                $t = $recommendationRating[2];
                $recommendationRating[2] = $recommendationRating[1];
                $recommendationRating[1] = $t;

                $t = $recommendation3;
                $recommendation3 = $recommendation2;
                $recommendation2 = $t;
            }
        }

        $data['recommendations'] = collect([$recommendation1, $recommendation2, $recommendation3]);
        $data['actual_cs'] = $recommendations;
        $data['estimated_rating'] = $recommendationRating;
        $data['distances'] = collect([$cs_dist_1, $cs_dist_2, $cs_dist_3]);
        $data['durations'] = collect([$cs_dur_1, $cs_dur_2, $cs_dur_3]);

        return view('recommend.recommend', compact('data'));
    }

    function calculateSimilarityScores($cs1, $cs2) {
        $chargingStationModel = new ChargingStation();
        $cs_att_1 = $chargingStationModel->getChargingStationAttributes($cs1);
        $cs_att_2 = $chargingStationModel->getChargingStationAttributes($cs2);

         $ab = $cs_att_1[0]->ac_ports_fast * $cs_att_2[0]->ac_ports_fast +
            $cs_att_1[0]->dc_ports_fast * $cs_att_2[0]->dc_ports_fast +
            $cs_att_1[0]->ac_ports_regular * $cs_att_2[0]->ac_ports_regular +
            $cs_att_1[0]->dc_ports_regular * $cs_att_2[0]->dc_ports_regular +
            $this->distance_scale($cs_att_1[0]->nearest_restaurant) * $this->distance_scale($cs_att_2[0]->nearest_restaurant) +
            $this->distance_scale($cs_att_1[0]->nearest_shopping_mall) * $this->distance_scale($cs_att_2[0]->nearest_shopping_mall) +
            $this->distance_scale($cs_att_1[0]->nearest_cinema_hall) * $this->distance_scale($cs_att_2[0]->nearest_cinema_hall);

        $aSquared = $cs_att_1[0]->ac_ports_fast * $cs_att_1[0]->ac_ports_fast +
            $cs_att_1[0]->dc_ports_fast * $cs_att_1[0]->dc_ports_fast +
            $cs_att_1[0]->ac_ports_regular * $cs_att_1[0]->ac_ports_regular +
            $cs_att_1[0]->dc_ports_regular * $cs_att_1[0]->dc_ports_regular +
            $this->distance_scale($cs_att_1[0]->nearest_restaurant) * $this->distance_scale($cs_att_1[0]->nearest_restaurant) +
            $this->distance_scale($cs_att_1[0]->nearest_shopping_mall) * $this->distance_scale($cs_att_1[0]->nearest_shopping_mall) +
            $this->distance_scale($cs_att_1[0]->nearest_cinema_hall) * $this->distance_scale($cs_att_1[0]->nearest_cinema_hall);

        $bSquared = $cs_att_2[0]->ac_ports_fast * $cs_att_2[0]->ac_ports_fast +
            $cs_att_2[0]->dc_ports_fast * $cs_att_2[0]->dc_ports_fast +
            $cs_att_2[0]->ac_ports_regular * $cs_att_2[0]->ac_ports_regular +
            $cs_att_2[0]->dc_ports_regular * $cs_att_2[0]->dc_ports_regular +
            $this->distance_scale($cs_att_2[0]->nearest_restaurant) * $this->distance_scale($cs_att_2[0]->nearest_restaurant) +
            $this->distance_scale($cs_att_2[0]->nearest_shopping_mall) * $this->distance_scale($cs_att_2[0]->nearest_shopping_mall) +
            $this->distance_scale($cs_att_2[0]->nearest_cinema_hall) * $this->distance_scale($cs_att_2[0]->nearest_cinema_hall);

        if ($aSquared == 0 || $bSquared == 0) {
            $similarityScore = 0;
        } else {
            $similarityScore = $ab / (sqrt($aSquared) * sqrt($bSquared));
        }
        return $similarityScore;
    }

    function distance_scale($distance_str) {
        $distance =  (float)$distance_str;
        if ($distance == 0) {
            return 0;
        } else if($distance <= 50) {
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

