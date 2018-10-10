<?php

declare(strict_types=1);

namespace Api\Model;

interface AggregateRoot
{
    public function releaseEvents(): array;
}
