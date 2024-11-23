<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Actions;

use Nomiai\PhpSdk\Resources\Nomi;

/** @mixin \Nomiai\PhpSdk\Traits\MakesHttpRequests */
trait ManagesNomis
{
    /**
     * Returns all the Nomis that are associated with your account.
     *
     * @see https://api.nomi.ai/docs/reference/get-v1-nomis
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     *
     * @return array<int, \Nomiai\PhpSdk\Resources\Nomi>
     */
    public function getNomis(): array
    {
        $response = $this->get('/v1/nomis');

        return array_map(fn(array $n): Nomi => Nomi::make($n), $response);
    }

    /**
     * Return the details of a specific Nomi associated to your account.
     *
     * @see https://api.nomi.ai/docs/reference/get-v1-nomis-id
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function getNomi(string $id): Nomi
    {
        $response = $this->get("/v1/nomis/{$id}");

        return Nomi::make($response);
    }
}
