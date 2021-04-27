<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases;

use Prokl\RequestValidatorSanitizer\Validation\Custom\JsonValidator;
use Prokl\TestingTools\Base\BaseTestCase;

/**
 * Class JsonValidatorTest
 * @package Tests\Services
 * @coversDefaultClass JsonValidator
 *
 */
class JsonValidatorTest extends BaseTestCase
{
    /**
     * @var JsonValidator $obTestObject Тестируемый объект.
     */
    protected $obTestObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new JsonValidator();
    }

    /**
     * passes(). Валидные json.
     *
     * @dataProvider dataProviderValidJson
      *
     * @param mixed $json
     */
    public function testPassesValidJson($json) : void
    {
        $result = $this->obTestObject->passes('', $json);

        $this->assertTrue(
            $result,
            'Нормальный json ' . $json . ' не прошел валидацию.'
        );
    }

    /**
     * passes(). Невалидные json.
     *
     * @dataProvider dataProviderJsonInvalid
     *
     * @param mixed $json
     */
    public function testPassesInvalidJson($json) : void
    {
        $result = $this->obTestObject->passes('', $json);

        $this->assertFalse(
            $result,
            'Ненормальный json ' . $json . ' прошел валидацию.'
        );
    }

    /**
     * Нормальные json.
     *
     * @return string[][]
     *
     */
    public function dataProviderValidJson(): array
    {
        return [
          [json_encode(['test' => 1])],
          [json_encode(['testing' => 'xx'])],
        ];
    }

    /**
     * Ненормальные email.
     *
     * @return string[][]
     */
    public function dataProviderJsonInvalid(): array
    {
        return [
            ['email'],
            ['@xxxx.ru'],
            ['xxxxx'],
            [23],
            [null]
        ];
    }
}
