<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use DateTimeImmutable;
use InvalidArgumentException;
use Nomiai\PhpSdk\Enums\RoomStatus;

readonly class Room extends Resource
{
    /**
     * Unique identifier
     * TSDoc UUID
     */
    public string $uuid;

    /**
     * Name of the room
     * TSDoc String
     */
    public string $name;

    /**
     * The date when the room was created
     * TSDoc ISODateString
     */
    public DateTimeImmutable $created;

    /**
     * The date when the room was updated
     * TSDoc ISODateString
     */
    public DateTimeImmutable $updated;

    /**
     * The status of the room
     * TSDoc StringEnum
     */
    public RoomStatus $status;

    /**
     * Whether the room has backchanneling enabled
     * TSDoc Boolean
     */
    public bool $backchannelingEnabled;

    /**
     * The Nomis currently in the room
     * TSDoc Nomi[]
     *
     * @var array<int, \Nomiai\PhpSdk\Resources\Nomi>
     */
    public array $nomis;

    /**
     * The shared notes attached to the room
     * TSDoc String
     */
    public string $note;

    /**
     * Room constructor.
     *
     * @param array<string, mixed> $nomis
     *
     * @throws \DateMalformedStringException
     */
    public function __construct(
        string $uuid,
        string $name,
        string|DateTimeImmutable $created,
        string|DateTimeImmutable $updated,
        string|RoomStatus $status,
        bool $backchannelingEnabled,
        array $nomis,
        string $note,
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->backchannelingEnabled = $backchannelingEnabled;
        $this->note = $note;

        $this->status = $status instanceof RoomStatus
            ? $status
            : (
                RoomStatus::tryFrom($status)
                ?? throw new InvalidArgumentException('This is not a valid status!')
            );
        $this->created = $created instanceof DateTimeImmutable
            ? $created
            : new DateTimeImmutable($created);

        $this->updated = $updated instanceof DateTimeImmutable
            ? $updated
            : new DateTimeImmutable($updated);

        $this->nomis = array_map(fn(array $nomi): Nomi => Nomi::make($nomi), $nomis);
    }

    /** @inheritDoc */
    public static function make(array $response): static
    {
        return new self(
            uuid: $response['uuid'],
            name: $response['name'],
            created: $response['created'],
            updated: $response['updated'],
            status: $response['status'],
            backchannelingEnabled: $response['backChannelingEnabled'],
            nomis: $response['nomis'],
            note: $response['note'],
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'created' => $this->created->format(self::ISO8601),
            'updated' => $this->updated->format(self::ISO8601),
            'status' => $this->status->value,
            'backChannelingEnabled' => $this->backchannelingEnabled,
            'nomis' => array_map(fn(Nomi $nomi): array => $nomi->toArray(), $this->nomis),
            'note' => $this->note,
        ];
    }
}
