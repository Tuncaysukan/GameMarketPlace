<?php

namespace Modules\Listing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Listing\Entities\Listing;

class SaveListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:10000',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'nullable|integer|min:0',
            'in_stock' => 'nullable|boolean',
            'sku' => 'nullable|string|max:100|unique:listings,sku,' . $this->route('id'),
            'delivery_type' => 'required|in:automatic,manual',
            'manual_delivery_note' => 'nullable|string|max:1000',
            'processing_days' => 'nullable|integer|min:1|max:30',
            'images' => 'nullable|array|max:10',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'stock_items' => 'nullable|array',
            'stock_items.*' => 'nullable|string|max:500',
            'filters' => 'nullable|array',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.required' => 'Kategori seçimi zorunludur.',
            'title.required' => 'İlan başlığı zorunludur.',
            'description.required' => 'Açıklama zorunludur.',
            'price.required' => 'Fiyat zorunludur.',
            'delivery_type.required' => 'Teslimat tipi seçimi zorunludur.',
            'manual_delivery_note.required_if' => 'Manuel teslimat için not girmelisiniz.',
            'stock_items.required_if' => 'Otomatik teslimat için stok eklemelisiniz.',
            'images.max' => 'Maksimum 10 görsel yükleyebilirsiniz.',
        ];
    }
}

