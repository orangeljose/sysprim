<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    private array $errors;
    private array $context;

    /**
     * BaseException constructor.
     * @param string $message
     * @param int $code
     * @param array $errors
     * @param array $context
     */
    public function __construct($message, int $code, array $errors = [], array $context = [])
    {
        parent::__construct($message ?? trans('exceptions.filter'), $code);
        $this->errors = $errors;
        $this->context = $context;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $data
     */
    public function setContext(array $data)
    {
        $this->context = $data;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return array_merge([
//            'message' => $this->getMessage(),
//            'trace' => $this->getTraceAsString()
        ], $this->context);
    }

    /**
     * @return array
     */
    public function context(): array
    {
        return $this->getContext();
    }
}
