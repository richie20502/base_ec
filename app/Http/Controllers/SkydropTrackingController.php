<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SkydropTracking;

class SkydropTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($dataTracking, $customerId, $order)
    {   
        Log::info("--------------- INI DATA TRACKING---------------");
        Log::info(json_encode($dataTracking));
        Log::info("--------------- FIN DATA TRACKING---------------");
        $data = json_decode($dataTracking->getContent(), true);
        $tracking = new SkydropTracking();
        $tracking->customer_id = $customerId;  
        $tracking->tracking_number = $data['shipment_id'];
        $tracking->carrier_name = $data['rate_id'];
        $tracking->order_id = $order; 
        $tracking->quotation_id = $data['quotation_id'];
        $tracking->save();
         
    }

   
}
