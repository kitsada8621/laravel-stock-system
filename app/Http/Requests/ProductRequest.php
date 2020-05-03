<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'p_id' => 'required',
            'p_name' => 'required',
            'p_price' => 'required|numeric',
            'unit_type_id' => 'required',
            'p_type_id' => 'required',
        ];
    }
}
