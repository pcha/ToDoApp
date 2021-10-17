<?php
namespace App\Exceptions;


use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Throwable;

class AttributeNotFoundException extends BadRequestException
{
    public function __construct(string $field)
    {
        parent::__construct("The attribute $field is mandatory");
    }
}