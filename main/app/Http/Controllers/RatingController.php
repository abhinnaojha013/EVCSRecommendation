<?php

namespace App\Http\Controllers;

use App\Models\Provinces;
use App\Models\Ratings;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    function provideRating() {
        $provinceModel = new Provinces();
        $data['provinces'] = $provinceModel->selectProvinces();
        return view('ratings/provideRating', compact('data'));
    }

    function addRating(Request $request) {
        try {
            $ratingModel = new Ratings();
            $ratingModel->insertRating($request);

            request()->session()->flash('success', 'Rating successfully provided.');
            // index of user
            return redirect()->route('');
        } catch (\Exception $exception) {
            request()->session()->flash('error', 'Rating provision failed.');
            return redirect()->route('rating.provide');
        }
    }
}
