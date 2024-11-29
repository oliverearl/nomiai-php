<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Tests\Fixtures;

readonly class ResourceFake extends \Nomiai\PhpSdk\Resources\Resource
{
    /** @inheritDoc */
    public function toArray(): array
    {
        return [
            'foo' => 'bar',
        ];
    }

    /** @inheritDoc */
    public static function make(array $response): static
    {
        return new self();
    }
}
