<?php

namespace App\Exceptions;

use Exception;

class InternalException extends Exception
{
    protected $error_code;

    public function __construct(string $message = "系统内部错误", int $code = 500, int $error_code = 0)
    {
        parent::__construct($message, $code);
        $this->error_code = $error_code;
    }

    public function render()
    {
        return response()->json([
            'code' => $this->error_code,
            'message' => $this->message
        ], $this->code);
    }
}
