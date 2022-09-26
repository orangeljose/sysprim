<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class NameRule implements Rule
{
    const REG = "/[a-zA-Z0-9\-_.\s]+/"; // Alphanumeric with spaces, underscores and dashes
    const FORBIDDEN_CHARS = [
        '\\',
        '\'',
        '?',
        '¿',
        '!',
        '¡',
        '`',
        '´',
        ':',
        '=',
        '"',
        '·',
        '$',
        '%',
//        '&', Hay nombres que utilizan este símbolo
        '/',
        'º',
        'ª',
        '<',
        '>',
        '*',
        '+',
        '~',
        '@',
        '|',
        '#',
        '¬',
    ];
    private array $except;

    public function __construct(array $except = [])
    {
        $this->except = $except;
    }

    /**
     * @var mixed
     */
    private $value;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (! $value) {
            return true;
        }

        $this->value = $value;

        $forbidden = collect(self::FORBIDDEN_CHARS)
            ->filter(fn (string $char) => ! in_array($char, $this->except))
            ->toArray();

        return preg_match(self::REG, $value)
            && mb_strlen($value) < 255
            && ! Str::contains($value, $forbidden);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('validation.invalid', ['value' => $this->value]);
    }
}