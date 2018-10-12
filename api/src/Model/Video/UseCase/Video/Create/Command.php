<?php

declare(strict_types=1);

namespace Api\Model\Video\UseCase\Video\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $author;
    /**
     * @Assert\NotBlank()
     */
    public $file;
}