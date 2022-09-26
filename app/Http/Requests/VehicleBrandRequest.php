<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleBrandRequest extends FormRequest
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
                'name' => ['required','string', 'unique:vehicle_brands,name', new NameRule()],
            ];
        } else {
            return [
                'name' => ['string', 'unique:vehicle_brands,name', new NameRule()],
            ];
        }
    }
}
