<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases;

use Prokl\RequestValidatorSanitizer\Validation\Custom\CyrillicAlphaValidator;
use Prokl\TestingTools\Base\BaseTestCase;

/**
 * Class LaravelPhoneValidatorTest
 * @package Tests\Services
 * @coversDefaultClass CyrillicAlphaValidator
 *
 * @since 07.09.2020
 * @since 13.10.2020 Испытание нового стороннего дата-провайдера.
 */
class CyrillicAlphaValidatorTest extends BaseTestCase
{
    /**
     * @var CyrillicAlphaValidator $obTestObject Тестируемый объект.
     */
    protected $obTestObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new CyrillicAlphaValidator();
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
            [null],
        ];
    }
}
