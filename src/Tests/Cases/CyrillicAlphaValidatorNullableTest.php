<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases;

use Prokl\RequestValidatorSanitizer\Validation\Custom\CyrillicAlphaValidatorNullable;
use Prokl\TestingTools\Base\BaseTestCase;

/**
 * Class LaravelPhoneValidatorTest
 * @package Tests\Services
 * @coversDefaultClass CyrillicAlphaValidatorNullable
 *
 * @since 07.09.2020
 * @since 13.10.2020 Испытание нового стороннего дата-провайдера.
 */
class CyrillicAlphaValidatorNullableTest extends BaseTestCase
{
    /**
     * @var CyrillicAlphaValidatorNullable $obTestObject Тестируемый объект.
     */
    protected $obTestObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new CyrillicAlphaValidatorNullable();
    }

    /**
     * passes(). Валидные данные.
     *
     * @dataProvider dataProviderValidData
      *
     * @param mixed $data
     */
    public function testPassesValidData($data) : void
    {
        $result = $this->obTestObject->passes('', $data);

        $this->assertTrue(
            $result,
            'Нормальный элемент ' . $data . ' не прошел валидацию.'
        );
    }

    /**
     * passes(). Невалидные данные.
     *
     * @dataProvider dataProviderInvalidData
     *
     * @param mixed $data
     */
    public function testPassesInvalidData($data) : void
    {
        $result = $this->obTestObject->passes('', $data);

        $this->assertFalse(
            $result,
            'Ненормальный элемент ' . $data . ' прошел валидацию.'
        );
    }

    /**
     * Валидные данные.
     *
     * @return string[][]
     *
     */
    public function dataProviderValidData(): array
    {
        return [
          ['ываыаыаыфвафы'],
          ['чясмчсмчясмчсям'],
          ['фывфывфы'],
          ['lllll'],
          [' lllll '],
          [null]
        ];
    }

    /**
     * Ненормальные телефонные номера.
     *
     * @return string[][]
     */
    public function dataProviderInvalidData(): array
    {
        return [
            ['8922508'],
            [1128642],
            ['23'],
        ];
    }
}
