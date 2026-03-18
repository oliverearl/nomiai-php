<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Exceptions\ValidationException;

describe('chat message validation', function (): void {
    it('throws validation exception for nomi message exceeding 800 characters', function (): void {
        $nomiId = $this->faker->uuid();
        $longMessage = str_repeat('a', 801);

        $api = $this->dummy(
            uri: "/nomis/{$nomiId}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [],
        );

        $api->sendMessage($nomiId, $longMessage);
    })->throws(ValidationException::class);

    it('allows nomi message with exactly 800 characters', function (): void {
        $nomiId = $this->faker->uuid();
        $maxMessage = str_repeat('a', 800);

        $api = $this->dummy(
            uri: "/nomis/{$nomiId}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [
                'sentMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => $maxMessage,
                    'sent' => (new DateTimeImmutable())->format('Y-m-d\TH:i:s.vT'),
                ],
                'replyMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => 'Reply',
                    'sent' => (new DateTimeImmutable())->format('Y-m-d\TH:i:s.vT'),
                ],
            ],
        );

        expect($api->sendMessage($nomiId, $maxMessage))->not->toBeNull();
    });

    it('validates message length for multibyte characters', function (): void {
        $nomiId = $this->faker->uuid();
        // 801 emoji characters (each emoji is multiple bytes)
        $longMessage = str_repeat('😀', 801);

        $api = $this->dummy(
            uri: "/nomis/{$nomiId}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [],
        );

        $api->sendMessage($nomiId, $longMessage);
    })->throws(ValidationException::class);
});

describe('room message validation', function (): void {
    it('throws validation exception for room message exceeding 800 characters', function (): void {
        $roomId = $this->faker->uuid();
        $longMessage = str_repeat('a', 801);

        $api = $this->dummy(
            uri: "/rooms/{$roomId}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [],
        );

        $api->sendMessageToRoomById($roomId, $longMessage);
    })->throws(ValidationException::class);

    it('allows room message with exactly 800 characters', function (): void {
        $roomId = $this->faker->uuid();
        $maxMessage = str_repeat('a', 800);

        $api = $this->dummy(
            uri: "/rooms/{$roomId}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [
                'sentMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => $maxMessage,
                    'sent' => (new DateTimeImmutable())->format('Y-m-d\TH:i:s.vT'),
                ],
            ],
        );

        expect($api->sendMessageToRoomById($roomId, $maxMessage))->not->toBeNull();
    });
});
