<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','min:3'],
            'brand' => ['required','string','min:3'],
            'category_id' => ['required','exists:categories,id'],
            'price' => ['required','numeric'],
            'weight' => ['required','numeric'],
            'description' => ['required','string','min:3'],
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => 'Category'
        ];
    }

    // Change Cents to dollar
    // public function prepareForValidation()
    // {
    //     $this->merge([
    //         'price' => $this->price * 100,
    //     ]);
    // }
}
