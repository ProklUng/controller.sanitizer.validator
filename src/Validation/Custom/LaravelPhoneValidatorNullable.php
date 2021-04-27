<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Custom;

/**
 * Class LaravelPhoneValidatorNullable
 * Валидатор телефона, но с возможностью пустого значения.
 * @package Fedy\Services\Validation\Custom
 *
 * @since 08.10.2020
 */
class LaravelPhoneValidatorNullable extends LaravelPhoneValidator
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed $attribute Аттрибут.
     * @param  mixed $value     Значение.
     *
     * @return null|boolean
     */
    public function passes($attribute, $value): ?bool
    {
        if ($value === null || $value === '' || $value === 'null') {
            return true;
        }

        return parent::passes($attribute, $value);
    }
}
