<?php

declare(strict_types=1);

namespace Api\Model;

interface EventDispatcher
{
    public function dispatch(...$events): void;
}
