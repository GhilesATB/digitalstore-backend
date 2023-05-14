<?php

namespace App\Traits;

trait StatusCodeResponseTrait
{
    public function withStatusCode(int $statusCode)
    {
        return $this->response()->setStatusCode($statusCode);
    }
}
