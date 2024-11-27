<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use JsonSerializable;

abstract readonly class Resource implements JsonSerializable
{
    /**
     * Return a new resource based on the API response.
     *
     * @param array<string, mixed> $response
     *
     * @return static
     */
    abstract public static function make(array $response): static;

    /**
     * Return an array representation of this resource.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /** @inheritDoc */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
