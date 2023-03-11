<?php

namespace App\Http\Controllers;

use App\Models\Provinces;
use App\Models\Ratings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    function index() {
        if (Auth::user()) {
            $userModel = new User();
            $ratingModel = new Ratings();
            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 3) {
                $data['ratings'] = $ratingModel->getUserChargingStationRating($user[0]->id);
                return view('ratings.index', compact('data'));
            }
        } else {
            return  redirect()->route('login');
        }
    }

    function provideRating() {
        if(Auth::user()) {
            $userModel = new User();
            $provinceModel = new Provinces();

            $user = $userModel->getLoggedInUser(Auth::id());
            if ($user[0]->role == 3) {
                $data['provinces'] = $provinceModel->selectProvinces();
                return view('ratings/provideRating', compact('data'));
            } else {
                return  redirect()->route('login');
            }
        } else {
            return  redirect()->route('login');
        }
    }

    function addRating(Request $request) {
        try {
            $ratingModel = new Ratings();

            $oldUserRatings = $ratingModel->oldUserRating($request);
            if ($oldUserRatings->isEmpty()) {
                $ratingModel->insertRating($request);
                request()->session()->flash('success', 'Rating successfully provided.');
                return redirect()->route('ratings.index');
            } else {
                request()->session()->flash('error', 'Rating already provided to this charging station.');
                return redirect()->route('rating.provide');
            }
        } catch (\Exception $exception) {
            request()->session()->flash('error', 'Rating provision failed.');
            return redirect()->route('rating.provide');
        }
    }
}
