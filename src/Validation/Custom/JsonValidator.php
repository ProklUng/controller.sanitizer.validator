<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Custom;

use Illuminate\Contracts\Validation\Rule;
use JsonException;

/**
 * Class JsonValidator
 * @package Prokl\RequestValidatorSanitizer\Validation\Custom
 *
 * @since 16.09.2020
 */
class JsonValidator implements Rule
{
    /**
     * @param  mixed $attribute Аттрибут.
     * @param  mixed $value     Значение.
     *
     * @return boolean
     */
    public function passes($attribute, $value): bool
    {
        return $this->isJson($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return ':attribute should be a valid json.';
    }

    /**
     * Проверка на JSON.
     *
     * @param mixed $value Проверяемое значение.
     *
     * @return boolean
     */
    private function isJson($value) : bool
    {
        if (!$value || !is_string($value)) {
            return false;
        }

        try {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            return true;
        } catch (JsonException $e) {
            return false;
        }
    }
}
