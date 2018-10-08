<?php

declare(strict_types=1);

namespace Api\Test\Feature\Auth\SignUp;

use Api\Test\Feature\WebTestCase;

class RequestTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            RequestFixture::class,
        ]);

        parent::setUp();
    }

    public function testMethod(): void
    {
        $response = $this->get('/auth/signup');
        self::assertEquals(405, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $response = $this->post('/auth/signup', [
            'email' => 'test-mail@example.com',
            'password' => 'test-password',
        ]);

        self::assertEquals(201, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'email' => 'test-mail@example.com',
        ], $data);
    }

    public function testNotValid(): void
    {
        $response = $this->post('/auth/signup', [
            'email' => 'incorrect-mail',
            'password' => 'short',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'errors' => [
                'email' => 'This value is not a valid email address.',
                'password' => 'This value is too short. It should have 6 characters or more.',
            ],
        ], $data);
    }

    public function testExisting(): void
    {
        $response = $this->post('/auth/signup', [
            'email' => 'test@example.com',
            'password' => 'test-password',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'User with this email already exists.',
        ], $data);
    }
}
