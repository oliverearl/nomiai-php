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
use Nomiai\PhpSdk\Resources\Message;
use Nomiai\PhpSdk\Resources\MessageSet;
use Nomiai\PhpSdk\Resources\Nomi;
use Tomb1n0\GuzzleMockHandler\GuzzleMockHandler;
use Tomb1n0\GuzzleMockHandler\GuzzleMockResponse;

describe('chats', function () {
    it('can send a message and receive a response to a nomi', function (): void {
        $message = $this->faker->paragraphs(asText: true);
        $nomiId = $this->faker->uuid();
        $messageSet = new MessageSet(
            sent: new Message(
                $this->faker->uuid(),
                $message,
                new DateTimeImmutable(),
            ),
            reply: new Message(
                $this->faker->uuid(),
                $this->faker->paragraphs(asText: true),
                new DateTimeImmutable(),
            ),
        );

        $handler = new GuzzleMockHandler();
        $response = new GuzzleMockResponse("/v1/nomis/{$nomiId}/chat");
        $response
            ->withMethod(HttpMethod::POST)
            ->withStatus(HttpStatus::OK)
            ->withBody($messageSet->toArray());
        $handler->expect($response);

        $api = new NomiAI('', '', new Client(['handler' => HandlerStack::create($handler)]));

        // When using helper method, expect the same reply!
        $nomi = new Nomi(
            uuid: $nomiId,
            name: $this->faker->firstName(),
            created: new DateTimeImmutable(),
            gender: $this->faker->randomElement(Gender::cases()),
            relationshipType: $this->faker->randomElement(RelationshipType::cases()),
        );

        foreach ([$api->sendMessage($nomiId, $message), $api->sendMessageToNomi($nomi, $message)] as $conversation) {
            expect($conversation)->toBeInstanceOf(MessageSet::class)
                ->sent->uuid->toEqual($messageSet->sent->uuid)
                ->reply->uuid->toEqual($messageSet->reply->uuid);
        }
    });
});
