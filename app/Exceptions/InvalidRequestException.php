<?php

namespace App\Exceptions;

use Exception;

class InvalidRequestException extends Exception
{
    protected $error_code;
    protected $errors;

    public function __construct(string $message = "", int $code = 400, int $error_code = 0, array $errors = [])
    {
        parent::__construct($message, $code);
        $this->error_code = $error_code;
        $this->errors = $errors;
    }

    public function render()
    {
        $response = [
            'code' => $this->error_code,
            'message' => $this->message
        ];

        if (!empty($this->errors))
            $response['errors'] = $this->errors;

        return response()->json($response);
    }
}
