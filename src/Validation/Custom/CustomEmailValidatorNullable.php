<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Custom;

/**
 * Class CustomEmailValidatorNullable
 * Валидатор email, но с возможностью пустого значения.
 * @package Fedy\Services\Validation\Custom
 *
 * @since 08.10.2020
 * @since 09.10.2020 Фикс ошибки.
 */
class CustomEmailValidatorNullable extends CustomEmailValidator
{
     /**
     * Determine if the validation rule passes.
     *
     * @param  mixed $attribute Аттрибут.
     * @param  mixed $value     Значение.
     *
     * @return boolean
     */
    public function passes($attribute, $value = null): bool
    {
        if ($value === null || $value === '' || $value === 'null') {
            return true;
        }

        return parent::passes($attribute, $value);
    }
}
