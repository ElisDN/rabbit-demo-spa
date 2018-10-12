<?php

declare(strict_types=1);

namespace Api\Test\Feature\Author\Create;

use Api\Test\Feature\AuthFixture;
use Api\Test\Feature\WebTestCase;

class ExistsTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            'auth' => AuthFixture::class,
            'author' => Fixture::class,
        ]);

        parent::setUp();
    }

    public function testGuest(): void
    {
        $response = $this->get('/author');
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testError(): void
    {
        $auth = $this->getAuth();

        $response = $this->post('/author/create', [
            'name' => 'Name'
        ], $auth->getHeaders());

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'Author already exists.',
        ], $data);
    }

    private function getAuth(): AuthFixture
    {
        return $this->getFixture('auth');
    }
}

