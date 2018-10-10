<?php

declare(strict_types=1);

namespace Api\Model\User\UseCase\SignUp\Confirm;

use Api\Model\EventDispatcher;
use Api\Model\Flusher;
use Api\Model\User\Entity\User\Email;
use Api\Model\User\Entity\User\UserRepository;

class Handler
{
    private $users;
    private $flusher;
    private $dispatcher;

    public function __construct(UserRepository $users, Flusher $flusher, EventDispatcher $dispatcher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
        $this->dispatcher = $dispatcher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new Email($command->email));

        $user->confirmSignup($command->token, new \DateTimeImmutable());

        $this->flusher->flush();

        $this->dispatcher->dispatch(...$user->releaseEvents());
    }
}
