<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleModelRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'name' => 'required|string',
                'vehicle_brand_id' => 'required|exists:vehicle_brands,id',
                'year' => 'required|string',
            ];
        } else {
            return [
                'name' => 'string',
                'vehicle_brand_id' => 'exists:vehicle_brands,id',
                'year' => 'string',
            ];
        }
    }
}
