<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\Address;
use App\Services\AuthService;
use App\Models\SkydropTracking;

class TrackingController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService = null)
    {
        $this->authService = $authService ?? new AuthService();
    }


    public function quotation(Request $request)
    {
        //dd($request->all());

        $productsFromRequest = $request->input('products');
        $completeProducts = [];

        foreach ($productsFromRequest as $productData) {
            $product = Product::find($productData['id']);
            $completeProducts[] = (object) [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'content' => $product->content,
                'status' => $product->status,
                'images' => $product->images,
                'sku' => $product->sku,
                'start_date' => $product->start_date,
                'end_date' => $product->end_date,
                'length' => $product->length,
                'wide' => $product->wide,
                'height' => $product->height,
                'weight' => $product->weight,
                'tax_id' => $product->tax_id,
                'views' => $product->views,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'stock_status' => $product->stock_status,
                'created_by_id' => $product->created_by_id,
                'created_by_type' => $product->created_by_type,
                'image' => $product->image,
                'product_type' => $product->product_type,
                'barcode' => $product->barcode,
                'cost_per_item' => $product->cost_per_item,
                'generate_license_code' => $product->generate_license_code,
                'minimum_order_quantity' => $product->minimum_order_quantity,
                'maximum_order_quantity' => $product->maximum_order_quantity,
                'notify_attachment_updated' => $product->notify_attachment_updated,
                'specification_table_id' => $product->specification_table_id,
                'store_id' => $product->store_id,
                'approved_by' => $product->approved_by
            ];

        }


        $quotationData = [
            'quotation' => [
                'address_from' => [
                    'country_code' => env('ADDRESS_COUNTRY_CODE'), // Valor por defecto
                    'postal_code' => env('ADDRESS_POSTAL_CODE'),
                    'area_level1' => env('ADDRESS_AREA_LEVEL1'),
                    'area_level2' => env('ADDRESS_AREA_LEVEL2'),
                    'area_level3' => env('ADDRESS_AREA_LEVEL3'),
                    'street1' => env('ADDRESS_STREET1'),
                    'apartment_number' => env('ADDRESS_APARTMENT_NUMBER'), // Puede ser nulo
                    'reference' => env('ADDRESS_REFERENCE'),
                    'name' => env('ADDRESS_NAME'),
                    'company' => env('ADDRESS_COMPANY'),
                    'phone' => env('ADDRESS_PHONE'),
                    'email' => env('ADDRESS_EMAIL'),
                ],
                'address_to' => [
                    'country_code' => 'mx',
                    'postal_code' => $request->zipCode,
                    'area_level1' => $request->city,
                    'area_level2' => $request->state,
                    'area_level3' => 'Monterrey Centro',
                    'street1' => $request->address,
                    #'apartment_number' => '3a',
                    'reference' => "referencia",
                    'name' => $request->fullName,
                    'company' => "",
                    'phone' => $request->phone,
                    'email' => $request->email,
                ],
                'parcel' => [
                    'length' => 10,
                    'width' => 10,
                    'height' => 10,
                    'weight' => 1,
                ],
                'requested_carriers' => [
                    'fedex',
                    'dhl',
                    'paquetexpress',
                    'sendex',
                    'quiken',
                    #'ninetynineminutes',
                    'jtexpress',
                ],
            ],
        ];
            Log::info("INI QUOTATION DATA");
            Log::info($quotationData);
            Log::info("FIN QUOTATION DATA");
        try {
            $response = $this->authService->createQuotation($quotationData);


            session()->forget('quotation_id');
            if (isset($response['id'])) {
                session(['quotation_id' => $response['id']]);
            }

            $responseDataQuotation = $this->authService->getQuotationById();
            Log::info(session('quotation_id'));
            Log::info("INI RESPONSE QUOTATION");
            Log::info($responseDataQuotation);
            Log::info("FIN RESPONSE QUOTATION");

            return response()->json([
                'status' => 'success',
                'message' => 'Quotation created successfully.',
                'data' => $responseDataQuotation,
            ], 200);


        
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    public function processRate (Request $request, $products){
        //dd($products);
        //dd($request->all());

        $productArray = []; // Inicializa un array vacío
        foreach ($products as $product) {
            // Agrega cada producto al array
            $productArray[] = [
                'name' => $product->name, //si
                'description' => $product->description, //si
                'quantity' => 1, //si seteado con uno ahorita
                'price' => $product->price,// si va 
                'sale_price' => $product->sale_price, //si
                "hs_code"=> "1234567890",
                'product_type_code'=> "P",
                'product_type_name'=> "Producto",
                'country_code' => 'MX',
                'weight' => $product->weight, //si 
                'weight_unit' => $product->weight,
            ];
        }


        //dd($productArray);

        $total = $request->input('sub_total');

        $company = "Zensara";

        if (isset($request->address['address_id'])) {
            if($request->address['address_id'] == 'new'){
                $name = $request->address['name'];
                $email = $request->address['email'];
                $phone = $request->address['phone'];
                $street = $request->address['address'];
                $postalCode = $request->address['zip_code'];
                
                $number = $request->address['phone'];
                $district = $request->address['country'];;
                $city = $request->address['city'];
                $state = $request->address['state'];
    
            }else{
    
                $add = Address::find($request->address['address_id']);
                $name = $add->name;
                $email = $add->email;
                $phone =  $add->phone;
                $street =  $add->address;
                $postalCode =  $add->zip_code;
                
                $number =  $add->phone;
                $district =  $add->country;
                $city =  $add->city;
                $state =  $add->state;
    
            }

        }else{
            $name = $request->address['name'];
            $email = $request->address['email'];
            $phone = $request->address['phone'];
            $street = $request->address['address'];
            $postalCode = $request->address['zip_code'];
                
            $number = $request->address['phone'];
            $district = $request->address['country'];;
            $city = $request->address['city'];
            $state = $request->address['state'];
        }

        
        
      
        
        

        $quotationId = $request->quoteSelection;
        $rateId =  $request->rateSelection;
        
        $reference = "referencias";

        $data_shipment = [
            'shipment' => [
                'quotation_id' => $quotationId,
                'rate_id' => $rateId,
                'protected' => true,
                'declared_value' => 1400,
                'printing_format' => "thermal",
                "address_from" => [
                    'country_code' => env('ADDRESS_COUNTRY_CODE'), 
                    'postal_code' => env('ADDRESS_POSTAL_CODE'),
                    'area_level1' => env('ADDRESS_AREA_LEVEL1'),
                    'area_level2' => env('ADDRESS_AREA_LEVEL2'),
                    'area_level3' => env('ADDRESS_AREA_LEVEL3'),
                    'street1' => env('ADDRESS_STREET1'),
                    'apartment_number' => env('ADDRESS_APARTMENT_NUMBER'),
                    'reference' => env('ADDRESS_REFERENCE'),
                    'name' => env('ADDRESS_NAME'),
                    'company' => env('ADDRESS_COMPANY'),
                    'phone' => env('ADDRESS_PHONE'),
                    'email' => env('ADDRESS_EMAIL'),
                ],
                "address_to" => [
                    "country_code"=> "mx",
                    "postal_code"=> $postalCode,
                    "area_level1"=> $state,
                    "area_level2"=>  $city,
                    "area_level3"=> $street,
                    "street1"=> $street,
                    "name"=> $name,
                    "company"=> $company,
                    "phone"=> $phone,
                    "email"=> $email,
                    "reference"=> $reference
                ],
                "consignment_note"=> "53102400",
                "package_type"=> "4G",
                "products" => $productArray
            ],
        ];

        $response = $this->authService->createShipment($data_shipment);

        return response()->json([
            'message' => 'Tarifa seleccionada correctamente.',
            'shipment_id' => $response['data']['id'],
            'rate_id' => $rateId,
            'quotation_id' => $quotationId,
        ]);


    }


    public function tracking(){
        $authCustomer = auth('customer')->id();
        $trackings = SkydropTracking::where('customer_id', $authCustomer)->get();


        $jsonData = $trackings->map(function ($tracking) {
            return [
                'tracking_number' => $tracking->tracking_number,
                'customer_id' => $tracking->customer_id,
                'carrier_name' => $tracking->carrier_name,
                'order_id' => $tracking->order_id,
                'quotation_id' => $tracking->quotation_id,
            ];
        });
    
        // Convertir a JSON
        $jsonResult = json_encode($jsonData, JSON_PRETTY_PRINT);
        dd(vars: $jsonResult);
    }
}
