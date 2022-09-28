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
        if ($this->isMethod('post')) {
            return [
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'plate' => ['required', new PlateRule(), Rule::unique('vehicles', 'plate')],
                'color' => ['required','min:3','max:15'],
                'entry_date' => 'required',
            ];
        } else {
            return [
                'vehicle_model_id' => 'required|exists:vehicle_models,id',
                'plate' => ['required', new PlateRule(), Rule::unique('vehicles', 'plate')->ignore($this->id_original, 'id')],
                'color' => ['required','min:3','max:15'],
                'entry_date' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            'plate.unique' => 'Esta placa ya se encuentra registrada con otro vehiculo.',
            'plate.required' => 'La placa es requerida.',
            'vehicle_model_id.exists' => 'El modelo seleccionado es invalido.',
            'color.required' => 'El color es requerido.',
            'color.min' => 'La fecha de entrada es requerida.',
            'color.max' => 'La fecha de entrada es requerida.',
            'entry_date.required' => 'La fecha de entrada es requerida.',
        ];
    }
}
