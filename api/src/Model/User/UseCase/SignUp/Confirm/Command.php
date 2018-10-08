<?php

declare(strict_types=1);

namespace Api\Model\User\UseCase\SignUp\Confirm;

class Command
{
    public $email;
    public $token;
}
