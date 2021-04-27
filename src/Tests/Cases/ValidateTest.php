<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases;

use Prokl\RequestValidatorSanitizer\Validation\Controllers\ValidateableTrait;
use Prokl\RequestValidatorSanitizer\Validation\Exceptions\ValidateErrorException;
use Prokl\TestingTools\Base\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ValidateTest
 * @package Prokl\RequestValidatorSanitizer\Tests\Cases
 *
 * @since 27.04.2021
 */
class ValidateTest extends BaseTestCase
{
    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new class {
            use ValidateableTrait;

            private function getRules(): array
            {
                return [
                    'name' => 'required|string',
                    'id' => 'string|nullable|min:3|max:60',
                ];
            }
        };
    }

    /**
     * Нормальная валидация.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testValidation(array $value) : void
    {
        $result = $this->obTestObject->validate(
            $value,
            [
                'name' => 'required|string',
                'id' => 'string|nullable|min:3|max:60',
            ]
        );

        // Если валидация не пройдет, то будет исключение.
        $this->assertTrue($result);

    }

    /**
     * Нормальная валидация из свойства.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testValidationFromProp(array $value) : void
    {
        $this->obTestObject = new class {
            use ValidateableTrait;

            private $rules = [
                'name' => 'required|string',
                'id' => 'string|nullable|min:3|max:60',
            ];
        };

        $result = $this->obTestObject->validate($value);

        // Если валидация не пройдет, то будет исключение.
        $this->assertTrue($result);
    }

    /**
     * Невалидные данные из свойства.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testValidationFromPropInvalidData(array $value) : void
    {
        $this->obTestObject = new class {
            use ValidateableTrait;

            private $rules = [
                    'name' => 'required|int',
                    'id' => 'string|nullable|min:3000|max:60000',
                ];

        };

        $this->expectException(ValidateErrorException::class);
        $this->obTestObject->validate($value);
    }

    /**
     * Request. Из свойства.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testValidationRequestFromProp(array $value) : void
    {
        $this->obTestObject = new class {
            use ValidateableTrait;

            private $rules = [
                'name' => 'required|string',
                'id' => 'string|nullable|min:3|max:60',
            ];
        };

        $result = $this->obTestObject->validateRequest($this->getRequest($value, 'POST'));
        $this->assertTrue($result);

        $result = $this->obTestObject->validateRequest($this->getRequest($value, 'GET'));
        $this->assertTrue($result);
    }

    /**
     * Request. Разные методы запроса.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testValidatingRequestFromPropInvalidMethods(array $value) : void
    {
        $this->obTestObject = new class {
            use ValidateableTrait;

            private $rules = [
                'name' => 'required|string',
                'id' => 'string|nullable|min:3|max:60',
            ];
        };

        $this->helperMiscMethods($value, 'PUT');
        $this->helperMiscMethods($value, 'OPTIONS');
        $this->helperMiscMethods($value, 'HEAD');
        $this->helperMiscMethods($value, 'DELETE');
        $this->helperMiscMethods($value, 'PATCH');
    }

    /**
     * @return array
     */
    public function dataProviderData() : array
    {
        return [
          [['name' => 'Иванов', 'id' => '2221']],
          [['name' => 'Петров Иванович Петр', 'id' => '22.4']],
        ];
    }

    /**
     * Тестовый Request.
     *
     * @param array  $params Параметры запроса.
     * @param string $method Способ запроса.
     *
     * @return Request
     */
    private function getRequest(array $params, string $method) : Request
    {
        $request = new Request(
            $params,
            $params,
        );

        $request->setMethod($method);

        return $request;
    }

    /**
     * @param array $value
     * @param string $method
     *
     * @return void
     */
    private function helperMiscMethods(array $value, string $method) : void
    {
        $result = $this->obTestObject->validateRequest($this->getRequest($value, $method));

        $this->assertTrue($result, 'Валидация сработала неправильно');
    }
}
