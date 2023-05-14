<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BaseApiException extends Exception
{
    public const MSG_PREFIX = "Error";

    public function __construct($message = null, $code = 0, Exception $e = null)
    {
        $message = $message ?? static::MSG_PREFIX;
        parent::__construct($message, $code, $e);
    }

    public function render(): JsonResponse
    {
        return new JsonResponse([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }
}
