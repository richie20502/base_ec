<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function quotation(Request $request){
        dd($request->all());
    }
}
