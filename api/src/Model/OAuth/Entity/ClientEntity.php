<?php

declare(strict_types=1);

namespace Api\Model\OAuth\Entity;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }
    public function setRedirectUri($uri): void
    {
        $this->redirectUri = $uri;
    }
}
