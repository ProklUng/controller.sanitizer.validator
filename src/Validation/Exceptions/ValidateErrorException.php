<?php

namespace Prokl\RequestValidatorSanitizer\Validation\Exceptions;


use Prokl\BaseException\BaseException;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;

/**
 * Class ValidateErrorException
 * @package RequestValidatorSanitizer\Validation\Exceptions
 *
 * @since 10.09.2020
 */
class ValidateErrorException extends BaseException implements RequestExceptionInterface
{

}
