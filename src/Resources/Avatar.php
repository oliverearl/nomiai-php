<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use InvalidArgumentException;
use Stringable;

readonly class Avatar extends Resource implements Stringable
{
    /**
     * Avatar constructor.
     */
    public function __construct(public string $avatar) {}

    /** @inheritDoc */
    public static function make(array $response): static
    {
        // Response is just the raw image data as a string in an array
        $avatar = reset($response) ?: throw new InvalidArgumentException('No valid avatar provided!');

        /** @phpstan-ignore new.static */
        return new static($avatar);
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return [
            'avatar' => $this->avatar,
        ];
    }

    /** @inheritDoc */
    public function __toString(): string
    {
        return $this->avatar;
    }
}
