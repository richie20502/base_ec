<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Botble\Ecommerce\Models\Product;
use App\Services\AuthService;

class TrackingController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
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
                    'country_code' => 'mx',
                    'postal_code' => 50000,
                    'area_level1' => 'Nuevo León',
                    'area_level2' => 'Monterrey',
                    'area_level3' => 'Monterrey Centro',
                    'street1' => 'Padre Raymundo Jardón 925',
                    'apartment_number' => '3a',
                    'reference' => 'Casa roja',
                    'name' => 'Homero Simpson',
                    'company' => 'Skydropx',
                    'phone' => 8100998879,
                    'email' => 'homero.simpson@gmail.com',
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
                    'ninetynineminutes',
                    'jtexpress',
                ],
            ],
        ];
            Log::info($quotationData);
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
}
