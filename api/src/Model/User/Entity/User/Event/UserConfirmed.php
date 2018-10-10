<?php

declare(strict_types=1);

namespace Api\Model\User\Entity\User\Event;

use Api\Model\User\Entity\User\UserId;

class UserConfirmed
{
    public $id;

    public function __construct(UserId $id)
    {
        $this->id = $id;
    }
}
