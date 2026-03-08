<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\User $user */
        $user = $this->user();

        /** @var Product $product */
        $product = $this->route('product');

        return $user !== null && $product !== null && $user->can('update', $product);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Product $product */
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,' . ($product?->id ?? 'NULL')],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

