<?php

declare(strict_types=1);

namespace Api\Model\User\UseCase\SignUp\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;
    /**
     * @Assert\NotBlank()
     */
    public $token;
}
