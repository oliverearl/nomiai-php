<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Tests;

use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use Nomiai\PhpSdk\NomiAI;
use Nomiai\PhpSdk\Resources\Nomi;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Tomb1n0\GuzzleMockHandler\GuzzleMockHandler;
use Tomb1n0\GuzzleMockHandler\GuzzleMockResponse;

class TestCase extends BaseTestCase
{
    /**
     * Faker generator used for producing bogus data.
     */
    protected readonly Generator $faker;

    /** @inheritdoc  */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    /**
     * Creates a dummy Nomi.ai API with a particular HTTP request mocked out.
     *
     * @param array<string, mixed> $headers
     */
    public function dummy(string $uri, string $method, int $status, mixed $body = null, array $headers = []): NomiAI
    {
        $handler = new GuzzleMockHandler();

        $response = (new GuzzleMockResponse($uri))
            ->withMethod($method)
            ->withStatus($status)
            ->withHeaders($headers)
            ->withBody($body);

        $handler->expect($response);

        return new NomiAI(
            token: $this->faker->uuid(),
            client: new Client(['handler' => HandlerStack::create($handler)]),
        );
    }

    /**
     * Generate a Nomi for testing purposes.
     */
    public function nomi(): Nomi
    {
        return Nomi::make([
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'created' => $this->faker->date(Nomi::ISO8601),
            'gender' => ($this->faker->randomElement(Gender::cases()))->value,
            'relationshipType' => ($this->faker->randomElement(RelationshipType::cases()))->value,
        ]);
    }
}
