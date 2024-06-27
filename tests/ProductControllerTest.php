<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ProductControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->setUserToken($this->client);
    }


    public function testCreateProduct(): void
    {
        $this->client->request('POST', '/api/products', [], [], [], json_encode([
            'name'  => 'Test Product ' . time(),
            'price' => 99.99,
        ], JSON_THROW_ON_ERROR));

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('success', $responseContent);
        $this->assertTrue($responseContent[ 'success' ]);
    }


    public function testGetProduct(): void
    {
        $productId = $this->getUuid();

        $this->client->request('GET', '/api/products/' . $productId);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('success', $responseContent);
        $this->assertTrue($responseContent[ 'success' ]);
    }


    public function testUpdateProduct(): void
    {
        $productId = $this->getUuid();

        $this->client->request('PUT', '/api/products/' . $productId, [], [], [], json_encode([
            'name'  => 'Updated Test Product',
            'price' => 79.99,
        ], JSON_THROW_ON_ERROR));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('success', $responseContent);
        $this->assertTrue($responseContent[ 'success' ]);
    }

    /**
     * @throws \JsonException
     */
    public function testListProducts(): void
    {
        $this->client->request('GET', '/api/products');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertArrayHasKey('success', $responseContent);
        $this->assertTrue($responseContent[ 'success' ]);
        $this->assertArrayHasKey('data', $responseContent);
        $this->assertIsArray($responseContent[ 'data' ]);
    }

    /**
     * @return void
     */
    public function testDeleteProduct(): void
    {
        $productId = $this->getUuid();

        $this->client->request('DELETE', '/api/products/' . $productId);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Sets the user token for the client.
     *
     * @param \Symfony\Bundle\FrameworkBundle\KernelBrowser $client The client instance.
     *
     * @return void
     */
    private function setUserToken(\Symfony\Bundle\FrameworkBundle\KernelBrowser $client): void
    {
        $useRepo = static::getContainer()->get(UserRepository::class);
        $testUser = $useRepo->findOneByUsername(UserFixtures::CREDENTIALS[ 'username' ]);
        $client->loginUser($testUser, 'login');
    }

    /**
     * Retrieves the last UUID from the Product repository.
     *
     * @return string The last UUID.
     */
    private function getUuid(): string
    {
        return static::getContainer()->get(ProductRepository::class)->findLastUuid();
    }
}
