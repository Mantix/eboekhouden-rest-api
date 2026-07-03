<?php

namespace Mantix\EBoekhoudenRestApi\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Mantix\EBoekhoudenRestApi\Client;
use Mantix\EBoekhoudenRestApi\EBoekhoudenException;
use Mantix\EBoekhoudenRestApi\Filter;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase {
    /**
     * @var MockHandler
     */
    private $mockHandler;

    /**
     * @var Client
     */
    private $client;

    protected function setUp(): void {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);

        $this->client = new Client(
            'test_token',
            'TestApp'
        );

        // Replace the Guzzle client with our mocked one
        $reflectionProperty = new \ReflectionProperty(Client::class, 'client');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->client, $guzzleClient);
    }

    public function testCreateSession() {
        // Mock the session creation response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'token' => 'test_session_token',
            'expiresIn' => 3600,
        ])));

        // Call the method under test
        $result = $this->client->createSession();

        // Assert the result is as expected
        $this->assertEquals('test_session_token', $result['token']);
        $this->assertEquals(3600, $result['expiresIn']);
    }

    public function testCreateSessionThrowsExceptionOnError() {
        // Mock an error response
        $this->mockHandler->append(new Response(400, [], json_encode([
            'code' => 'API_SESSION_001',
            'message' => 'Source is missing',
            'title' => 'Bad Request',
            'status' => 400,
        ])));

        // Expect an exception
        $this->expectException(EBoekhoudenException::class);
        $this->expectExceptionMessage('Source is missing');

        // Call the method under test
        $this->client->createSession();
    }

    public function testGetRelationsWithFilters() {
        $container = [];
        $history = Middleware::history($container);

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $handlerStack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $handlerStack]);

        $reflectionProperty = new \ReflectionProperty(Client::class, 'client');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->client, $guzzleClient);

        $this->mockHandler->append(new Response(200, [], json_encode([
            'token' => 'test_session_token',
            'expiresIn' => 3600,
        ])));

        $this->mockHandler->append(new Response(200, [], json_encode([
            'items' => [],
            'count' => 0,
        ])));

        $this->client->getRelations([
            'name' => Filter::like('%Bedrijf%'),
            'code' => Filter::notEq('REL001'),
            'amount' => Filter::gte(100),
            'date' => Filter::dateRange('2023-01-01', '2023-12-31'),
        ]);

        $request = $container[1]['request'];
        $queryString = $request->getUri()->getQuery();

        $this->assertStringContainsString('name%5Blike%5D=%25Bedrijf%25', $queryString);
        $this->assertStringContainsString('code%5Bnot_eq%5D=REL001', $queryString);
        $this->assertStringContainsString('amount%5Bgte%5D=100', $queryString);
        $this->assertStringContainsString('date%5Brange%5D=2023-01-01%2C2023-12-31', $queryString);
    }

    public function testGetRelations() {
        // Mock the session creation response (needed for auto session management)
        $this->mockHandler->append(new Response(200, [], json_encode([
            'token' => 'test_session_token',
            'expiresIn' => 3600,
        ])));

        // Mock the get relations response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'items' => [
                [
                    'id' => 1,
                    'type' => 'B',
                    'code' => 'R0001',
                ],
                [
                    'id' => 2,
                    'type' => 'P',
                    'code' => 'R0002',
                ],
            ],
            'count' => 2,
        ])));

        // Call the method under test
        $result = $this->client->getRelations();

        // Assert the result is as expected
        $this->assertCount(2, $result['items']);
        $this->assertEquals(2, $result['count']);
        $this->assertEquals('R0001', $result['items'][0]['code']);
        $this->assertEquals('R0002', $result['items'][1]['code']);
    }

    public function testCreateRelation() {
        // Mock the session creation response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'token' => 'test_session_token',
            'expiresIn' => 3600,
        ])));

        // Mock the create relation response
        $this->mockHandler->append(new Response(201, [], json_encode([
            'id' => 123,
            'code' => 'R0123',
        ])));

        // Data to create the relation
        $relationData = [
            'name' => 'Test Company',
            'address' => 'Test Street 123',
            'postalCode' => '1234 AB',
            'city' => 'Test City',
        ];

        // Call the method under test
        $result = $this->client->createRelation($relationData);

        // Assert the result is as expected
        $this->assertEquals(123, $result['id']);
        $this->assertEquals('R0123', $result['code']);
    }

    public function testEndSession() {
        // Mock the session creation response
        $this->mockHandler->append(new Response(200, [], json_encode([
            'token' => 'test_session_token',
            'expiresIn' => 3600,
        ])));

        // Create a session first
        $this->client->createSession();

        // Mock the end session response
        $this->mockHandler->append(new Response(204));

        // Call the method under test
        $this->client->endSession();

        // There's nothing to assert here as the method doesn't return anything,
        // but if no exception is thrown, the test is considered a success
        $this->addToAssertionCount(1);
    }
}
