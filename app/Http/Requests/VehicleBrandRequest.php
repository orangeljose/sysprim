<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use App\Models\VehicleBrand;
use Illuminate\Validation\Rule;
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
                'name' => ['required','min:2','max:15',
                    Rule::unique('vehicle_brands')->where(function($query) {
                        $query->where('name', '=', $this->name)
                            ->whereNull('deleted_at');
                    })            
                ], 
                    new NameRule(),
            ];
        } else {
                return [
                    'name' => ['required','min:2','max:15',
                    Rule::unique('vehicle_brands')->where(function($query) {
                        $query->where('name', '=', $this->name)                                
                                ->whereNull('deleted_at');
                        })->ignore($this->id, 'id')      
                    ], 
                        new NameRule(),
                ];
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre de la marca es requerido.',
            'name.unique' => 'El nombre de la marca ya ha sido registrado.',
            'name.min' => 'El nombre debe contener al menos 2 caracteres.',
            'name.max' => 'El nombre debe contener máximo 15 caracteres.',
        ];
    }
}
