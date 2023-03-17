<?php

namespace App\Http\Controllers;

use App\Models\Provinces;
use App\Models\Ratings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;

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
        if ($request->get('province') < 1 || $request->get('province') > 7) {
            request()->session()->flash('error', 'Please select a valid province');
            return redirect()->route('rating.provide');
        } elseif ($request->get('district') < 1 || $request->get('district') > 77) {
            request()->session()->flash('error', 'Please select a valid district');
            return redirect()->route('rating.provide');
        } elseif ($request->get('metropolitan') < 0) {
            request()->session()->flash('error', 'Please select a valid metropolitan');
            return redirect()->route('rating.provide');
        } elseif ($request->get('ward_number') < 0 || $request->get('ward_number') > $request->get('max_wards')) {
            request()->session()->flash('error', 'Please select a valid ward');
            return redirect()->route('rating.provide');
        }

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

    function editRating(Request $request) {
        try {
            $ratingModel = new Ratings();
            $ratingModel->updateRating($request);
            request()->session()->flash('success', 'Successfully updated rating');
        } catch (\Exception $exception) {
            request()->session()->flash('error', 'Rating update failed.');
        } finally {
            return redirect()->route('ratings.index');
        }
    }
}
