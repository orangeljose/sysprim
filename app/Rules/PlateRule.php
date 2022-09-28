<?php

namespace App\Rules;

use App\Models\Vehicle;
use Illuminate\Contracts\Validation\Rule;

class PlateRule implements Rule
{
    private ?string $value;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param string $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->value = $value;

        if (empty($value)) {
            return false;
        }

        if (preg_match(Vehicle::PLATE_REGEX, trim(str_replace([' ', '-'], '', strtoupper($value))))) {
            return true;
        }

        if (preg_match('/(.)\1{5,}/', $value)) { // Avoid more than 5 characters duplicated
            return false;
        }

        if (! preg_match('/[a-zA-Z]/', $value)) { // At least one non-numeric value
            return false;
        }

        if (! preg_match('/[0-9]/', $value)) { // At least one numeric value
            return false;
        }

        return true; // TEMP
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La placa debe tener 1 carácter numérico, 1 no numérico y no mas de 5 caracteres iguales.';
    }
}
