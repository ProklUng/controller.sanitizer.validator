<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Custom;

use Illuminate\Contracts\Validation\Rule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * Class LaravelPhoneValidator
 * @package Fedy\Services\Validation\Custom
 *
 * @since 07.09.2020
 * @since 07.10.2020 Локализация сообщения об ошибке.
 */
class LaravelPhoneValidator implements Rule
{
    /** @const string DEFAULT_COUNTRY Код страны по умолчанию. */
    private const DEFAULT_COUNTRY = 'RU';

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed $attribute Аттрибут.
     * @param  mixed $value     Значение.
     *
     * @return boolean
     */
    public function passes($attribute, $value): bool
    {
        $defaultRegion = self::DEFAULT_COUNTRY;

        if ($value === null || !is_string($value) || $value === '') {
            return false;
        }

        if (strpos($value, '+') === 0) {
            $defaultRegion = null;
        }

        $phoneUtil = PhoneNumberUtil::getInstance();

        $phoneNumber = null;

        try {
            $phoneNumber = $phoneUtil->parse($value, $defaultRegion);
        } catch (NumberParseException $e) {
            return false;
        }

        $phoneUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);

        if ($phoneUtil->isValidNumber($phoneNumber) === false) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Номер телефона должен быть валидным.';
    }
}
