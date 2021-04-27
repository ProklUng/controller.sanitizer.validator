<?php

namespace Prokl\RequestValidatorSanitizer\Tests\Cases\Examples;

use Prokl\RequestValidatorSanitizer\Validation\Controllers\ValidateableTrait;
use Prokl\RequestValidatorSanitizer\Validation\Custom\LaravelPhoneValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SampleControllerValidated
 * @package Prokl\RequestValidatorSanitizer\Tests\Cases\Examples
 *
 * @since 08.09.2020
 */
class SampleControllerValidated extends AbstractController
{
    use ValidateableTrait;

    /**
     * @return array
     */
    protected function getRules() : array
    {
        return [
            'ID' => 'required|numeric',
            'TYPE' => 'required|string',
            'PHONE' => ['required', new LaravelPhoneValidator ],
        ];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function action(
        Request $request
    ): string {
        $this->validateRequest($request, $this->getRules());

        return 'OK';
    }
}
