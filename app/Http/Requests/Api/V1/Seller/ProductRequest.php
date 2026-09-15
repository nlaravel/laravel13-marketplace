<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Seller;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_id' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'integer',
            ],
            'category_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }
}
