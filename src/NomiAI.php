<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Nomiai\PhpSdk\Actions\ManagesChats;
use Nomiai\PhpSdk\Actions\ManagesNomis;
use Nomiai\PhpSdk\Traits\MakesHttpRequests;

class NomiAI
{
    use MakesHttpRequests;
    use ManagesChats;
    use ManagesNomis;

    /**
     * The default endpoint to use for the library.
     */
    public const string DEFAULT_ENDPOINT = 'https://api.nomi.ai';

    /**
     * NomiAI constructor.
     */
    public function __construct(
        private readonly string $token,
        private readonly string $endpoint = self::DEFAULT_ENDPOINT,
        private ?ClientInterface $client = null,
    ) {
        $this->client ??= new Client([
            'base_uri' => $this->endpoint . '/',
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => [
                'Authorization' => $this->token,
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
