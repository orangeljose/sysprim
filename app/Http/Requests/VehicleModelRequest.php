<?php

namespace App\Http\Requests;

use App\Models\VehicleBrand;
use Illuminate\Validation\Rule;
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
        $vehicle_brand_id = $this->vehicle_brand_id;
        $name = $this->name;
        $year = $this->year;
        if ($this->isMethod('post')) {
            return [
                'vehicle_brand_id' => 'required|exists:vehicle_brands,id',
                'name'=> ['required',
                    Rule::unique('vehicle_models')->where(function($query) {
                        $query->where('vehicle_brand_id', $this->vehicle_brand_id)
                            ->where('name',$this->name)
                            ->where('year',$this->year);
                    })            
                ],
                'year' => 'required|min:4|max:4',
            ];           
        }else{
                return [
                    'vehicle_brand_id' => 'required|exists:vehicle_brands,id',
                    'name'=> ['required',
                        Rule::unique('vehicle_models')->where(function($query) {
                            $query->where('vehicle_brand_id', $this->vehicle_brand_id)
                                ->where('name', $this->name)
                                ->where('year', $this->year);
                        })->ignore($this->id_original, 'id'),
                    ],                
                    'year' => 'required|min:4|max:4',
                ];                
        }

    }

    public function messages()
    {
        return [
            'vehicle_brand_id.required' => 'La marca del modelo es requerido.',
            'vehicle_brand_id.exists' => 'La marca del modelo seleccionada es invalida.',
            'name.unique' => 'El nombre del modelo ya se encuentra registrado con esta marca y año.',
            'name.required' => 'El nombre del modelo es requerido.',
            'year.required' => 'El año del modelo es requerido.',
            'year.min' => 'El año debe contener al menos 4 digitos.',
            'year.max' => 'El año debe contener máximo 4 digitos.',
        ];
    }

}
