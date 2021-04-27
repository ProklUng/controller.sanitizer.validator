<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases;

use Prokl\RequestValidatorSanitizer\Validation\Custom\LaravelPhoneValidator;
use Prokl\TestingTools\Base\BaseTestCase;

/**
 * Class LaravelPhoneValidatorTest
 * @package Tests\Services
 * @coversDefaultClass LaravelPhoneValidator
 *
 * @since 07.09.2020
 * @since 13.10.2020 Испытание нового стороннего дата-провайдера.
 */
class LaravelPhoneValidatorTest extends BaseTestCase
{
    /**
     * @var LaravelPhoneValidator $obTestObject Тестируемый объект.
     */
    protected $obTestObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new LaravelPhoneValidator();
    }

    /**
     * passes(). Валидные номера.
     *
     * @dataProvider dataProviderPhonesValid
      *
     * @param mixed $phone
     */
    public function testPassesValidPhone($phone) : void
    {
        $result = $this->obTestObject->passes('', $phone);

        $this->assertTrue(
            $result,
            'Нормальный телефон ' . $phone . ' не прошел валидацию.'
        );
    }

    /**
     * passes(). Невалидные номера.
     *
     * @dataProvider dataProviderPhonesInvalid
     *
     * @param mixed $phone
     */
    public function testPassesInvalidPhone($phone) : void
    {
        $result = $this->obTestObject->passes('', $phone);

        $this->assertFalse(
            $result,
            'Ненормальный телефон ' . $phone . ' прошел валидацию.'
        );
    }

    /**
     * Нормальные телефонные номера.
     *
     * @return string[][]
     *
     */
    public function dataProviderPhonesValid(): array
    {
        return [
          ['+38 044 555 1234'],
          ['89263622503'],
          ['+79263622503'],
          ['84996128644'],
          ['9263622503'],
          ['926-362-2503'],
          ['(926)362-25-01'],
          ['+380445551234'],
        ];
    }

    /**
     * Ненормальные телефонные номера.
     *
     * @return string[][]
     */
    public function dataProviderPhonesInvalid(): array
    {
        return [
            ['8922508'],
            ['phone'],
            ['xxxxx'],
            [1128647],
            ['23'],
        ];
    }
}
