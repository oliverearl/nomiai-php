<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Nomiai\PhpSdk\Traits\MakesHttpRequests;

class NomiAI
{
    use MakesHttpRequests;

    /**
     * NomiAI constructor.
     */
    public function __construct(
        private readonly string $token,
        private readonly string $endpoint,
        private ?ClientInterface $client = null,
    ) {
        $this->client ??= new Client([
            'http_errors' => false,
            'base_uri' => $this->endpoint . '/',
            'headers' => [
                'Authorization' => "Bearer {$this->token}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Returns the API token currently in use.
     */
    public function token(): string
    {
        return $this->token;
    }

    /**
     * Returns the endpoint currently in use.
     */
    public function endpoint(): string
    {
        return $this->endpoint;
    }
}
