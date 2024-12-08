<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Requests;

use Nomiai\PhpSdk\Resources\Nomi;

readonly class RoomRequest implements \JsonSerializable
{
    /**
     * RoomRequest constructor.
     */
    public function __construct(
        public ?string $name = null,
        public ?string $note = null,
        public ?bool $backchannelingEnabled = null,
        public ?array $nomiUuids = null,
    ) {}

    /**
     * Return an array representation of this resource.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'note' => $this->note,
            'backchannelingEnabled' => $this->backchannelingEnabled,
            'nomiUuids' => is_array($this->nomiUuids)
                ? array_map(fn(string|Nomi $nomi): string => $nomi instanceof Nomi
                    ? $nomi->uuid
                    : $nomi, $this->nomiUuids)
                : null,
        ]);
    }

    /** @inheritDoc */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
