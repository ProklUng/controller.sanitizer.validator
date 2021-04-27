<?php

namespace Cases;

use Prokl\RequestValidatorSanitizer\Sanitizing\SanitizableTrait;
use Prokl\TestingTools\Base\BaseTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SanitizableTest
 * @package Cases
 */
class SanitizableTest extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new class {
            use SanitizableTrait;

            private function getSanitizingRules(): array
            {
                return [
                    'id' => 'trim|escape|strip_tags|cast:string',
                ];
            }
        };
    }

    /**
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testSanitizing(array $value) : void
    {
        $result = $this->obTestObject->sanitize(
            $value,
            [
                'id' => 'trim|escape|strip_tags|cast:string',
            ]
        );

        $this->assertIsString($result['id'], 'Санация в строку не сработала');
    }

    /**
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testSanitizingFromProp(array $value) : void
    {
        $this->obTestObject = new class {
            use SanitizableTrait;

            protected $sanitizeRules = [
                'id' => 'trim|escape|strip_tags|cast:string'
            ];
        };

        $result = $this->obTestObject->sanitize($value);

        $this->assertIsString($result['id'], 'Санация в строку не сработала');
    }

    /**
     * Request.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testSanitizingRequestFromProp(array $value) : void
    {
        $this->obTestObject = new class {
            use SanitizableTrait;

            protected $sanitizeRules = [
                'id' => 'trim|escape|strip_tags|cast:string'
            ];
        };

        $response = $this->obTestObject->sanitizeRequest($this->getRequest($value, 'POST'));
        $result = $response->request->all();

        $this->assertIsString($result['id'], 'Санация в строку не сработала');

        $response = $this->obTestObject->sanitizeRequest($this->getRequest($value, 'GET'));
        $result = $response->query->all();

        $this->assertIsString($result['id'], 'Санация в строку не сработала');
    }

    /**
     * Request. Не поддерживаемые методы запроса.
     *
     * @param array $value
     *
     * @return void
     * @dataProvider dataProviderData
     */
    public function testSanitizingRequestFromPropInvalidMethods(array $value) : void
    {
        $this->obTestObject = new class {
            use SanitizableTrait;

            protected $sanitizeRules = [
                'id' => 'trim|escape|strip_tags|cast:string'
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
          [['id' => 1]],
          [['id' => 22.4]],
          [['id' => null]],
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
        $response = $this->obTestObject->sanitizeRequest($this->getRequest($value, $method));
        $result = $response->request->all();

        $this->assertIsNotString($result['id'], 'Санация сработала неправильно');
    }
}
