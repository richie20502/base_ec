<?php

namespace Botble\Ecommerce\Http\Requests\API;

use Botble\Ecommerce\Models\Product;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateCartRequest extends Request
{
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                Rule::exists(Product::class, 'id'),
            ],
            'qty' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'qty.required' => __('Quantity is required!'),
            'qty.integer' => __('Quantity must be a number!'),
        ];
    }
}