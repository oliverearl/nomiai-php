<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use Nomiai\PhpSdk\NomiAI;
use Nomiai\PhpSdk\Resources\Nomi;
use Tomb1n0\GuzzleMockHandler\GuzzleMockHandler;
use Tomb1n0\GuzzleMockHandler\GuzzleMockResponse;

describe('nomis', function (): void {
    it('can retrieve all nomis associated with an account', function (): void {
        $nomi = fn(): array => [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'created' => $this->faker->date(DateTimeImmutable::ATOM),
            'gender' => ($this->faker->randomElement(Gender::cases()))->value,
            'relationshipType' => ($this->faker->randomElement(RelationshipType::cases()))->value,
        ];

        $nomis = [
            $nomi(),
            $nomi(),
        ];

        $handler = new GuzzleMockHandler();
        $response = new GuzzleMockResponse('/v1/nomis');
        $response
            ->withMethod(HttpMethod::GET)
            ->withBody($nomis);
        $handler->expect($response);

        $api = new NomiAI('', '', new Client(['handler' => HandlerStack::create($handler)]));
        $retrievedNomis = $api->getNomis();

        foreach ($retrievedNomis as $index => $retrievedNomi) {
            expect($retrievedNomi)
                ->toBeInstanceOf(Nomi::class)
                ->toArray()->toEqual($nomis[$index]);
        }
    });
});

