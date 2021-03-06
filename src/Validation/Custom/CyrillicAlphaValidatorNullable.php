<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Custom;

/**
 * Class CyrillicAlphaValidator
 * @package Fedy\Services\Validation\Custom
 *
 * @since 17.10.2020
 */
class CyrillicAlphaValidatorNullable extends CyrillicAlphaValidator
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
        if ($value === null || $value === '') {
            return true;
        }

        return parent::passes($attribute, $value);
    }
}
