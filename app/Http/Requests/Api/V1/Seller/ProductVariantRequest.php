<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Seller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')
                    ->ignore($this->route('variant')),
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'compare_at_price' => [
                'nullable',
                'numeric',
                'min:0',
                'gte:price',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
