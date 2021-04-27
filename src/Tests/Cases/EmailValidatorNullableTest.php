<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases;

use Prokl\RequestValidatorSanitizer\Validation\Custom\CustomEmailValidatorNullable;
use Prokl\TestingTools\Base\BaseTestCase;

/**
 * Class EmailValidatorNullableTest
 * @package Tests\Services
 * @coversDefaultClass CustomEmailValidatorNullable
 *
 * @since 07.09.2020
 * @since 13.10.2020 Испытание нового стороннего дата-провайдера.
 */
class EmailValidatorNullableTest extends BaseTestCase
{
    /**
     * @var CustomEmailValidatorNullable $obTestObject Тестируемый объект.
     */
    protected $obTestObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = new CustomEmailValidatorNullable();
    }

    /**
     * passes(). Валидные email.
     *
     * @dataProvider dataProviderValidEmail
      *
     * @param mixed $email
     */
    public function testPassesValidEmail($email) : void
    {
        $result = $this->obTestObject->passes('', $email);

        $this->assertTrue(
            $result,
            'Нормальный email ' . $email . ' не прошел валидацию.'
        );
    }

    /**
     * passes(). Невалидные номера.
     *
     * @dataProvider dataProviderEmailInvalid
     *
     * @param mixed $email
     */
    public function testPassesInvalidEmail($email) : void
    {
        $result = $this->obTestObject->passes('', $email);

        $this->assertFalse(
            $result,
            'Ненормальный email ' . $email . ' прошел валидацию.'
        );
    }

    /**
     * Нормальные email.
     *
     * @return string[][]
     *
     */
    public function dataProviderValidEmail(): array
    {
        return [
          ['f@mail.ru'],
          ['zeko@corp.ru'],
          ['pro@corp.gogo.ru'],
          [null],
        ];
    }

    /**
     * Ненормальные email.
     *
     * @return string[][]
     */
    public function dataProviderEmailInvalid(): array
    {
        return [
            ['email'],
            ['@xxxx.ru'],
            ['xxxxx'],
            [23]
        ];
    }
}
