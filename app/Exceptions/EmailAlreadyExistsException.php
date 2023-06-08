<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmailAlreadyExistsException extends BaseApiException
{
    public const MSG_PREFIX = "Email name Already exists";

    public function __construct($message = null, Exception $e = null)
    {
        parent::__construct($message ?? self::MSG_PREFIX, Response::HTTP_BAD_REQUEST, $e);
    }
}
