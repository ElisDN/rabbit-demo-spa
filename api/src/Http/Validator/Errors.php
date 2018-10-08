<?php

declare(strict_types=1);

namespace Api\Http\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class Errors
{
    private $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
    }

    public function toArray(): array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }
}
