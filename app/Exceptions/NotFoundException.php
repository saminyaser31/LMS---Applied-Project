<?php

namespace App\Exceptions;

use Illuminate\Http\Response;
use Throwable;

class NotFoundException extends BaseException {
    public function __construct($message = "", $details = "", $code = 0, Throwable $previous = null)
    {
        $this->message = __('exception.not_found');

        if (!blank($message)) {
            $this->message = $message;
        }

        if (!$code) {
            $code = Response::HTTP_NOT_FOUND;
        }

        parent::__construct($details, $code, $previous);
    }
}

