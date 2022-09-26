<?php

namespace App\Http\Requests;

use App\Rules\PlateRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
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
        /** @var Vehicle $vehicle */
        $vehicle = $this->route('vehicle') ?? $this->vehicle;

        if ($this->isMethod('post')) {
            return [
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'plate' => ['required', new PlateRule(), Rule::unique('vehicles', 'plate')],
                'color' => 'required|string'
            ];
        } else {
            return [
                'vehicle_model_id' => 'exists:vehicle_models,id',
                'plate' => ['string', new PlateRule(), Rule::unique('vehicles', 'plate')->ignore($vehicle['id'] ?? null)],
                'color' => 'required|string'
            ];
        }
    }
}
