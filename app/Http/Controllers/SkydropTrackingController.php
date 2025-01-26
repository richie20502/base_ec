<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SkydropTracking;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;

use Botble\Ecommerce\Models\Order;

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
    public function create($dataTracking = null, $customerId, $order)
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


    public function create_dos($dataTracking = null, $customerId, $order)
    {

        $tracking = new SkydropTracking();
        $tracking->customer_id = $customerId;
        $tracking->tracking_number = 0;
        $tracking->carrier_name = 0;
        $tracking->order_id = $order;
        $tracking->quotation_id = 0;
        $tracking->save();

    }


    public function generateOrder($order_id = null)
    {
        $order_id = 25;
        $order = Order::find($order_id);
                // Recuperar los valores desde los parámetros de la URL, estableciendo valores por defecto
        $orderDetails = [
            'order_id' => $order->code,
            'provider_name' => 'Proveedor Desconocido',
            'total' =>  $order->amount,
            'days' => 'No especificado',
        ];

        // Enviar el correo con los detalles
        Mail::to('richie.lugo.recillas.1990@gmail.com')->send(new OrderShipped($orderDetails));

        return response()->json(['message' => 'Orden generada y correo enviado correctamente', 'orderDetails' => $orderDetails]);
    }


}
