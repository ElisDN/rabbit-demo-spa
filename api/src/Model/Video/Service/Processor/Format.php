<?php

declare(strict_types=1);

namespace Api\Model\Video\Service\Processor;

class Format
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}