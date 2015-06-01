<?php namespace BB\Exceptions;

use Illuminate\Support\MessageBag;

class FormValidationException extends \Exception
{

    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * @param string     $message
     * @param MessageBag $errors
     */
    public function __construct($message, MessageBag $errors)
    {
        $this->errors = $errors;

        parent::__construct($message);
    }

    /**
     * Get form validation errors
     *
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

}