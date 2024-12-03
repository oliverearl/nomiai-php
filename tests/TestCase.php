<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Tests;

use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use Nomiai\PhpSdk\Enums\RoomStatus;
use Nomiai\PhpSdk\NomiAI;
use Nomiai\PhpSdk\Resources\Nomi;
use Nomiai\PhpSdk\Resources\Room;
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
    public function dummy(string $uri, string $method, int $status, mixed $body = [], array $headers = []): NomiAI
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

    /**
     * Generate a room for testing purposes.
     */
    public function room(): Room
    {
        return Room::make([
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->company(),
            'created' => (new DateTimeImmutable())->format(Room::ISO8601),
            'updated' => (new DateTimeImmutable())->format(Room::ISO8601),
            'status' => $this->faker->randomElement(RoomStatus::cases())->value,
            'backchannelingEnabled' => $this->faker->boolean(),
            'note' => $this->faker->realText(),
            'nomis' => [
                $this->nomi()->toArray(),
                $this->nomi()->toArray(),
            ],
        ]);
    }
}
