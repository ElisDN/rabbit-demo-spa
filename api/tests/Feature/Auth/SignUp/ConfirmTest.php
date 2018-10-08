<?php

declare(strict_types=1);

namespace Api\Test\Feature\Auth\SignUp;

use Api\Test\Feature\WebTestCase;

class ConfirmTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->loadFixtures([
            ConfirmFixture::class,
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
        $response = $this->post('/auth/signup/confirm', [
            'email' => 'confirm@example.com',
            'token' => 'token',
        ]);

        self::assertEquals(200, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([], $data);
    }

    public function testNotValid(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => 'not-valid',
            'token' => '',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'errors' => [
                'email' => 'This value is not a valid email address.',
                'token' => 'This value should not be blank.',

            ],
        ], $data);
    }

    public function testNotExistingUser(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => 'not-found@example.com',
            'token' => 'token',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'User is not found.',
        ], $data);
    }

    public function testInvalidToken(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => 'confirm@example.com',
            'token' => 'incorrect',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'Confirm token is invalid.',
        ], $data);
    }

    public function testExpiredToken(): void
    {
        $response = $this->post('/auth/signup/confirm', [
            'email' => 'expired@example.com',
            'token' => 'token',
        ]);

        self::assertEquals(400, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertEquals([
            'error' => 'Confirm token is expired.',
        ], $data);
    }
}
