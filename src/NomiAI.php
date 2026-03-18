<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Nomiai\PhpSdk\Actions\ManagesAvatars;
use Nomiai\PhpSdk\Actions\ManagesChats;
use Nomiai\PhpSdk\Actions\ManagesNomis;
use Nomiai\PhpSdk\Actions\ManagesRooms;
use Nomiai\PhpSdk\Traits\MakesHttpRequests;

class NomiAI
{
    use MakesHttpRequests;
    use ManagesAvatars;
    use ManagesChats;
    use ManagesNomis;
    use ManagesRooms;

    /**
     * The HTTP client used to make API requests.
     */
    private ClientInterface $client;

    /**
     * The default endpoint to use for the library.
     */
    public const string DEFAULT_ENDPOINT = 'https://api.nomi.ai';

    /**
     * The default API version.
     */
    public const string DEFAULT_API_VERSION = 'v1';

    /**
     * The version of the API.
     */
    final public const string VERSION = '1.0.0';

    /**
     * NomiAI constructor.
     */
    public function __construct(
        private readonly string $token,
        private readonly string $endpoint = self::DEFAULT_ENDPOINT,
        ?ClientInterface $client = null,
        private readonly string $apiVersion = self::DEFAULT_API_VERSION,
    ) {
        if (empty($this->token)) {
            throw new InvalidArgumentException('NomiAI token is required.');
        }

        $this->client = $client ?? new Client([
            'base_uri' => $this->endpoint . '/' . $this->apiVersion . '/',
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => [
                'Authorization' => $this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => sprintf('Nomi.ai PHP SDK (%s)', self::VERSION),
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

    /**
     * Returns the API version currently in use.
     */
    public function apiVersion(): string
    {
        return $this->apiVersion;
    }
}
