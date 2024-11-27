<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use Nomiai\PhpSdk\NomiAI;
use Nomiai\PhpSdk\Resources\Nomi;
use Tomb1n0\GuzzleMockHandler\GuzzleMockHandler;
use Tomb1n0\GuzzleMockHandler\GuzzleMockResponse;

describe('nomis', function (): void {
    describe('index', function (): void {
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
                ->withStatus(HttpStatus::OK)
                ->withBody(['nomis' => $nomis]);
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

    describe('show', function (): void {
        it('can retrieve a nomi associated with an account', function (): void {
            $id = $this->faker->uuid();
            $nomi = [
                'uuid' => $id,
                'name' => $this->faker->name(),
                'created' => $this->faker->date(DateTimeImmutable::ATOM),
                'gender' => ($this->faker->randomElement(Gender::cases()))->value,
                'relationshipType' => ($this->faker->randomElement(RelationshipType::cases()))->value,
            ];

            $handler = new GuzzleMockHandler();
            $response = new GuzzleMockResponse("/v1/nomis/{$id}");
            $response
                ->withMethod(HttpMethod::GET)
                ->withStatus(HttpStatus::OK)
                ->withBody($nomi);
            $handler->expect($response);

            $api = new NomiAI('', '', new Client(['handler' => HandlerStack::create($handler)]));
            $retrievedNomi = $api->getNomi($id);

            expect($retrievedNomi)
                ->toBeInstanceOf(Nomi::class)
                ->uuid->toEqual($id)
                ->toArray()->toEqual($nomi);
        });
    });
});
