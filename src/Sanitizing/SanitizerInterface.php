<?php

namespace Prokl\RequestValidatorSanitizer\Sanitizing;

/**
 * Interface SanitizerInterface
 * @package Prokl\RequestValidatorSanitizer\Sanitizing
 *
 * @since 08.09.2020
 */
interface SanitizerInterface
{
    /**
     * Создать экземпляр Sanitizer.
     *
     * @param array $arData Данные.
     * @param array $rules  Схема санации.
     *
     * @return mixed
     */
    public function make(array $arData, array $rules);
}
