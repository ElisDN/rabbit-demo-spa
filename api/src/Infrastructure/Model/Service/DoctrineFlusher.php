<?php

declare(strict_types=1);

namespace Api\Infrastructure\Model\Service;

use Api\Model\Flusher;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineFlusher implements Flusher
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
