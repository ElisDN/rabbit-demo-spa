<?php

declare(strict_types=1);

namespace Api\Http\Validator;

use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($object): ?Errors
    {
        $violations = $this->validator->validate($object);
        if ($violations->count() > 0) {
            return new Errors($violations);
        }
        return null;
    }
}
