<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Traits;

use GuzzleHttp\RequestOptions;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/** @mixin \Nomiai\PhpSdk\NomiAI */
trait MakesHttpRequests
{
    /**
     * Make a GET request.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return null|string|array<string, mixed>
     */
    public function get(string $uri): string|array|null
    {
        return $this->request(HttpMethod::GET, $uri);
    }

    /**
     * Make a POST request.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return null|string|array<string, mixed>
     */
    public function post(string $uri, array $payload = []): string|array|null
    {
        return $this->request(HttpMethod::POST, $uri, $payload);
    }

    /**
     * Make a PUT request.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return null|string|array<string, mixed>
     */
    public function put(string $uri, array $payload = []): string|array|null
    {
        return $this->request(HttpMethod::PUT, $uri, $payload);
    }

    /**
     * Make a PATCH request.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return null|string|array<string, mixed>
     */
    public function patch(string $uri, array $payload = []): string|array|null
    {
        return $this->request(HttpMethod::PATCH, $uri, $payload);
    }

    /**
     * Make a DELETE request.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return null|string|array<string, mixed>
     */
    public function delete(string $uri, array $payload = []): string|array|null
    {
        return $this->request(HttpMethod::DELETE, $uri, $payload);
    }

    /**
     * Makes a HTTP request to the API.
     *
     * @param array<string, mixed> $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return null|string|array<string, mixed>
     */
    public function request(string $verb, string $uri, array $payload = []): null|string|array
    {
        $response = $this->client->request(
            method: $verb,
            uri: $uri,
            options: empty($payload) ? [] : [RequestOptions::FORM_PARAMS => $payload],
        );

        if ($this->isUnsuccessful($response)) {
            $this->handleRequestError($response);
        }

        $body = (string) $response->getBody();

        return json_decode($body, associative: true) ?: $body;
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
     * @throws \RuntimeException
     */
    private function handleRequestError(ResponseInterface $response): never
    {
        // TODO: Later, we'll throw exceptions based on code. For now, just throw.
        throw new RuntimeException('Error: ' . $response->getStatusCode());
    }
}
