<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Controllers;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use Prokl\RequestValidatorSanitizer\Validation\Dictionaries\DefaultValidationMessages as DefaultMessages;
use Prokl\RequestValidatorSanitizer\Validation\Exceptions\ValidateErrorException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ValidateableTrait
 * Трэйт валидации для контроллеров.
 * @package RequestValidatorSanitizer\Validation\Controllers
 *
 * @since 08.09.2020
 * @since 10.09.2020 Изменен тип исключений.
 * @since 02.11.2020 Рефакторинг. Валидация пустого Request (nullable возможности).
 */
trait ValidateableTrait
{
    /**
     * Валидирует Request.
     *
     * @param Request    $request Request.
     * @param array|null $rules   Правила валидации.
     *
     * @return boolean
     * @throws ValidateErrorException Ошибки валидации.
     */
    public function validateRequest(
        Request $request,
        array $rules = null
    ) : bool {
        $data = $this->getDataRequest($request);

        if (empty($data)) {
            throw new ValidateErrorException('Empty input params.');
        }

        return $this->validate(
            $data,
            $rules
        );
    }

    /**
     * Валидирует Request. C возможностью пустых параметров.
     *
     * @param Request    $request Request.
     * @param array|null $rules   Правила валидации.
     *
     * @return boolean
     * @throws ValidateErrorException Ошибки валидации.
     *
     * @since 02.11.2020
     */
    public function validateNullableRequest(
        Request $request,
        array $rules = null
    ) : bool {
        $data = $this->getDataRequest($request);

        return $this->validate(
            $data,
            $rules
        );
    }

    /**
     * Данные для валидации.
     *
     * @param Request $request Request.
     *
     * @return array
     *
     * @since 02.11.2020
     */
    private function getDataRequest(Request $request) : array
    {
        // Тип запроса.
        $typeRequest = $request->getMethod();

        return ($typeRequest === 'POST') ?
            $request->request->all()
            :
            $request->query->all();
    }

    /**
     * Валидирует переданные данные
     *
     * @param array      $data     Данные, подлежащие валидации.
     * @param array|null $rules    Правила валидации.
     * @param array|null $messages Сообщение.
     *
     * @return boolean
     * @throws ValidateErrorException Ошибки валидации.
     */
    public function validate(array $data, array $rules = null, array $messages = null): bool
    {
        $rules = $rules ?? (property_exists($this, 'rules') ? $this->rules : []);

        $validator = new Validator(
            new Translator(new ArrayLoader(), $this->getValidationLocale()),
            $data,
            $rules,
            $this->getValidationMessages($messages)
        );

        if ($validator->fails()) {
            throw new ValidateErrorException(implode(', ', $validator->errors()->all()));
        }

        return true;
    }

    /**
     * Валидирует отдельный атрибут.
     *
     * @param string     $attribute Атрибут.
     * @param mixed      $value     Значение.
     * @param array|null $rules     Правила валидации.
     *
     * @return boolean
     * @throws ValidateErrorException Ошибки валидации.
     */
    public function validateAttribute(string $attribute, $value, array $rules = null): bool
    {
        if (empty($rules)) {
            if (property_exists($this, 'rules') && !empty($this->rules[$attribute])) {
                $rules = [$attribute => $this->rules[$attribute]];
            } else {
                $rules = [$attribute => []];
            }
        }

        $validator = new Validator(
            new Translator(new ArrayLoader(), $this->getValidationLocale()),
            [$attribute => $value],
            $rules,
            $this->getValidationMessages()
        );

        if ($validator->fails()) {
            throw new ValidateErrorException(implode(', ', $validator->errors()->all()));
        }

        return true;
    }

    /**
     * getValidationLocale.
     *
     * @return  string
     */
    protected function getValidationLocale(): string
    {
        return property_exists($this, 'locale') ? $this->locale : 'en_US';
    }

    /**
     * getValidationMessages.
     *
     * @param array|null $messages Сообщение.
     *
     * @return  array
     */
    protected function getValidationMessages(array $messages = null): array
    {
        return $messages ?? (property_exists($this, 'messages') ? $this->messages : DefaultMessages::getItems());
    }
}
