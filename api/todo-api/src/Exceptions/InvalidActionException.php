<?php
namespace App\Exceptions;


use Throwable;

class InvalidActionException extends \Exception
{
    protected string $action;

    public function __construct(string $action, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("Invalid action: \"$action\"", $code, $previous);
    }
}