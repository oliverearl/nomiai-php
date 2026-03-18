<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Exceptions\NomiException;
use Nomiai\PhpSdk\NomiAI;
use Tomb1n0\GuzzleMockHandler\GuzzleMockHandler;
use Tomb1n0\GuzzleMockHandler\GuzzleMockResponse;

describe('HTTP request trait', function (): void {
    it('correctly identifies successful responses', function (): void {
        $api = $this->dummy(
            uri: '/nomis',
            method: HttpMethod::GET,
            status: HttpStatus::OK,
            body: ['nomis' => []],
        );

        expect($api->getNomis())->toBe([]);
    });

    it('correctly identifies unsuccessful responses', function (): void {
        $api = $this->dummy(
            uri: '/nomis',
            method: HttpMethod::GET,
            status: HttpStatus::NOT_FOUND,
            body: ['error' => ['type' => 'NomiNotFound']],
        );

        $api->getNomis();
    })->throws(NomiException::class);

    it('handles 401 unauthorized errors', function (): void {
        $api = $this->dummy(
            uri: '/nomis',
            method: HttpMethod::GET,
            status: HttpStatus::UNAUTHORIZED,
            body: ['error' => ['type' => 'Unauthorized']],
        );

        $api->getNomis();
    })->throws(NomiException::class, 'Authorization');

    it('handles 400 invalid API key errors', function (): void {
        $api = $this->dummy(
            uri: '/nomis',
            method: HttpMethod::GET,
            status: HttpStatus::BAD_REQUEST,
            body: ['error' => ['type' => 'InvalidAPIKey']],
        );

        $api->getNomis();
    })->throws(NomiException::class, 'UUID format');

    it('handles 429 rate limit errors', function (): void {
        $api = $this->dummy(
            uri: '/nomis',
            method: HttpMethod::GET,
            status: HttpStatus::TOO_MANY_REQUESTS,
            body: ['error' => ['type' => 'RateLimitExceeded']],
        );

        $api->getNomis();
    })->throws(NomiException::class, 'too many requests');

    it('handles malformed JSON responses', function (): void {
        $handler = new GuzzleMockHandler();
        $response = (new GuzzleMockResponse('/nomis'))
            ->withMethod(HttpMethod::GET)
            ->withStatus(HttpStatus::OK)
            ->withBody('this is not valid json');

        $handler->expect($response);

        $api = new NomiAI(
            token: $this->faker->uuid(),
            client: new Client([
                'handler' => HandlerStack::create($handler),
            ]),
        );

        $api->getNomis();
    })->throws(NomiException::class);

    it('sends POST requests with JSON encoding', function (): void {
        $nomiId = $this->faker->uuid();
        $message = 'Test message';

        $api = $this->dummy(
            uri: "/nomis/{$nomiId}/chat",
            method: HttpMethod::POST,
            status: HttpStatus::OK,
            body: [
                'sentMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => $message,
                    'sent' => (new DateTimeImmutable())->format('Y-m-d\TH:i:s.vT'),
                ],
                'replyMessage' => [
                    'uuid' => $this->faker->uuid(),
                    'text' => 'Reply',
                    'sent' => (new DateTimeImmutable())->format('Y-m-d\TH:i:s.vT'),
                ],
            ],
        );

        $result = $api->sendMessage($nomiId, $message);

        expect($result)->not->toBeNull();
    });

    it('handles empty response bodies gracefully', function (): void {
        $handler = new GuzzleMockHandler();
        $response = (new GuzzleMockResponse('/test'))
            ->withMethod(HttpMethod::GET)
            ->withStatus(HttpStatus::OK)
            ->withBody('');

        $handler->expect($response);

        $api = new NomiAI(
            token: $this->faker->uuid(),
            client: new Client([
                'handler' => HandlerStack::create($handler),
            ]),
        );

        $api->get('/test');
    })->throws(NomiException::class);
});
