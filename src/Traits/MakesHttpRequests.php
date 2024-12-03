<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Traits;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Nomiai\PhpSdk\Constants\ErrorResponse;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Exceptions\NomiException;
use Psr\Http\Message\ResponseInterface;

/** @mixin \Nomiai\PhpSdk\NomiAI */
trait MakesHttpRequests
{
    /**
     * Make a GET request.
     *
     * @return array<string, mixed>
     */
    public function get(string $uri): array
    {
        return $this->request(HttpMethod::GET, $uri);
    }

    /**
     * Make a POST request.
     *
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function post(string $uri, array $payload = []): array
    {
        return $this->request(HttpMethod::POST, $uri, $payload);
    }

    /**
     * Make a PUT request.
     *
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function put(string $uri, array $payload = []): array
    {
        return $this->request(HttpMethod::PUT, $uri, $payload);
    }

    /**
     * Make a PATCH request.
     *
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function patch(string $uri, array $payload = []): array
    {
        return $this->request(HttpMethod::PATCH, $uri, $payload);
    }

    /**
     * Make a DELETE request.
     *
     * @param array<string, mixed> $payload
     *
     * @return array<string, mixed>
     */
    public function delete(string $uri, array $payload = []): array
    {
        return $this->request(HttpMethod::DELETE, $uri, $payload);
    }

    /**
     * Makes a HTTP request to the API.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \Nomiai\PhpSdk\Exceptions\NomiException
     *
     * @return array<string, mixed>
     */
    public function request(string $verb, string $uri, array $payload = []): array
    {
        $body = $this->rawRequest($verb, $uri, $payload);

        return json_decode($body, associative: true) ?? throw new NomiException();
    }

    /**
     * Make a HTTP request to the API without attempting to decode the response.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \Nomiai\PhpSdk\Exceptions\NomiException
     */
    public function rawRequest(string $verb, string $uri, array $payload = []): string
    {
        try {
            $response = $this->client->request(
                method: $verb,
                uri: $uri,
                options: empty($payload) ? [] : [RequestOptions::FORM_PARAMS => $payload],
            );
        } catch (GuzzleException $e) {
            throw new NomiException($e->getMessage(), $e->getCode(), $e);
        }

        if ($this->isUnsuccessful($response)) {
            $this->handleRequestError($response);
        }

        return (string) $response->getBody();
    }

    /**
     * Returns whether a given HTTP request was successful.
     */
    public function isSuccessful(?ResponseInterface $response): bool
    {
        if (empty($response)) {
            return false;
        }

        return (int) substr((string) $response->getStatusCode(), 0, 1) === 2;
    }

    /**
     * Returns whether a given HTTP request was unsuccessful.
     */
    public function isUnsuccessful(?ResponseInterface $response): bool
    {
        return ! $this->isSuccessful($response);
    }

    /**
     * Handles the provided request error.
     *
     * @throws \Nomiai\PhpSdk\Exceptions\NomiException
     */
    private function handleRequestError(ResponseInterface $response): never
    {
        $error = json_decode($response->getBody()->getContents(), associative: true) ?: [];
        $type = $error['type'] ?? null;

        // Lookup tables are cool and absolutely not a code smell.
        match ($type) {
            // General
            ErrorResponse::RATE_LIMIT_EXCEEDED => throw NomiException::rateLimitExceeded(),
            ErrorResponse::INVALID_ROUTE_PARAMS => throw NomiException::invalidRouteParams(),
            ErrorResponse::INVALID_CONTENT_TYPE => throw NomiException::invalidContentType(),

            // Nomi / Chat:
            ErrorResponse::INVALID_BODY => throw NomiException::invalidBody($error['error']['issues'] ?? []),
            ErrorResponse::LIMIT_EXCEEDED => throw NomiException::limitExceeded(),
            ErrorResponse::MESSAGE_LENGTH_LIMIT_EXCEEDED => NomiException::messageLengthLimitExceeded(),
            ErrorResponse::NOMI_NOT_FOUND => throw NomiException::nomiNotFound(),
            ErrorResponse::NOMI_NOT_READY => throw NomiException::nomiNotReady(),
            ErrorResponse::NOMI_STILL_RESPONDING => throw NomiException::nomiStillResponding(),
            ErrorResponse::ONGOING_VOICE_CALL_DETECTED => throw NomiException::ongoingVoiceCallDetected(),
            ErrorResponse::NO_REPLY => throw NomiException::noReply(),

            // Avatars:
            ErrorResponse::NOT_FOUND => throw NomiException::notFound(),

            // Rooms:
            ErrorResponse::INSUFFICIENT_PLAN => throw NomiException::insufficientPlan(),
            ErrorResponse::EXCEEDED_ROOM_LIMIT => throw NomiException::exceededRoomLimit(),
            ErrorResponse::ROOM_NOMI_COUNT_TOO_SMALL => throw NomiException::roomNomiCountTooSmall(),
            ErrorResponse::ROOM_NOMI_COUNT_TOO_LARGE => throw NomiException::roomNomiCountTooLarge(),
            ErrorResponse::NOTE_NOT_ACCEPTED => throw NomiException::noteNotAccepted(),
            ErrorResponse::NO_RESPONSE_FROM_SERVER => throw NomiException::noResponseFromServer(),
            ErrorResponse::ROOM_NOT_FOUND => throw NomiException::roomNotFound(),

            default => throw new NomiException(),
        };

        throw new NomiException();
    }
}
