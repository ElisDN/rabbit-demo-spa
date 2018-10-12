<?php

declare(strict_types=1);

namespace Api\Test\Feature\Author\Video\Create;

use Api\Test\Feature\AuthFixture;
use Api\Test\Feature\WebTestCase;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\UploadedFile;
use Zend\Diactoros\Uri;

class SuccessTest extends WebTestCase
{
    private $path;

    protected function setUp(): void
    {
        $this->loadFixtures([
            'auth' => AuthFixture::class,
            'author' => Fixture::class,
        ]);

        $path = realpath('var/test');
        $this->initDemoFiles($path);
        $this->path = $path;

        parent::setUp();
    }

    public function testGuest(): void
    {
        $response = $this->post('/author/video/create');
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSuccess(): void
    {
        $auth = $this->getAuth();

        $response = $this->request(
            (new ServerRequest())
                ->withUri(new Uri('http://test/author/video/create'))
                ->withMethod('POST')
                ->withHeader('Authorization', $auth->getHeaders()['Authorization'])
                ->withUploadedFiles([
                    'file' => new UploadedFile(
                        $this->path . '/video.3gp',
                        filesize($this->path . '/video.3gp'),
                        0,
                        'video.3gp',
                        'application/octet-stream'
                    )
                ])
        );

        self::assertEquals(201, $response->getStatusCode());
        self::assertJson($content = $response->getBody()->getContents());

        $data = json_decode($content, true);

        self::assertArrayHasKey('id', $data);
        self::assertNotEmpty($data['id']);
    }

    private function getAuth(): AuthFixture
    {
        return $this->getFixture('auth');
    }

    protected function initDemoFiles(string $path): void
    {
        if (file_exists($path . '/video.3gp')) {
            unlink($path . '/video.3gp');
        }

        copy(__DIR__ . '/data/video.3gp', $path . '/video.3gp');
    }
}
