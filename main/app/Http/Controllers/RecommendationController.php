<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Provinces;
use App\Models\Ratings;
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
        $user_rating = $ratingsModel->userRatings();
        $user_location_rating = '';
        echo $request->get('ward_enabled');
//        if($user_rating->count() <= 3) {
//            request()->session()->flash('error', 'Please rate at least 3 charging stations.');
//            return redirect()->route('recommendations.index');
//        } else {
            if($request->get('ward_enabled') == 0) {
                $user_location_rating = $ratingsModel->getUsersRatingsNoWard($request);
            } elseif ($request->get('ward_enabled') == 1) {
                $user_location_rating = $ratingsModel->getUsersRatings($request);
            }

            // data pulled
            echo $user_rating;
            echo $user_location_rating;

//        }
    }
}
