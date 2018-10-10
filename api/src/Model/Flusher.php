<?php

declare(strict_types=1);

namespace Api\Model;

interface Flusher
{
    public function flush(AggregateRoot ...$root): void;
}
