<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class FilterException extends BaseException
{
    public function __construct(array $errors = [], array $context = [])
    {
        parent::__construct(trans('exceptions.filter'), Response::HTTP_BAD_REQUEST, $errors, $context);
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report(): ?bool
    {
        return false;
    }
}
