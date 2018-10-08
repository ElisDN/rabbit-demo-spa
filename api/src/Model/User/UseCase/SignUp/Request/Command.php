<?php

declare(strict_types=1);

namespace Api\Model\User\UseCase\SignUp\Request;

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
     * @Assert\Length(min=6)
     */
    public $password;
}
