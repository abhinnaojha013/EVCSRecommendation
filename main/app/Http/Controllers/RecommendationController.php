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
        if($user_rating->count() <= 3) {
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
                    if ($ur->charging_station == $csl->charging_station) {
                        $ratingEstimateNum += $ur->rating;
                        $ratingEstimateDen += 1;
                    } elseif ($ur->charging_station < $csl->charging_station) {
                        $ss = 0;
                        $ssCollection = $similarityScoreModel->getSimilarityScore($csl->charging_station, $ur->charging_station);
                        foreach ($ssCollection as $s) {
                            $ss = $s->similarity_score;
                        }
                        $ratingEstimateNum = $ratingEstimateNum + $ur->rating * $ss;
                        $ratingEstimateDen += $ss;
                    } elseif ($ur->charging_station > $csl->charging_station) {
                        $ss = 0;
                        $ssCollection = $similarityScoreModel->getSimilarityScore($ur->charging_station, $csl->charging_station);
                        foreach ($ssCollection as $s) {
                            $ss = $s->similarity_score;
                        }
                        $ratingEstimateNum = $ratingEstimateNum + $ur->rating * $ss;
                        $ratingEstimateDen += $ss;
                    }
                }
                $ratingEstimate = $ratingEstimateNum / $ratingEstimateDen;
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

        $data['recommendations'] = $chargingStationModel->getFinalRecommendations($recommendations[0], $recommendations[1], $recommendations[2]);
        return view('recommend.recommend', compact('data'));
    }
}
