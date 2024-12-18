<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use DateTimeImmutable;
use InvalidArgumentException;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;

readonly class Nomi extends Resource
{
    /**
     * Unique identifier
     * TSDoc UUID
     */
    public string $uuid;

    /**
     * Gender of the Nomi
     * TSDoc StringEnum
     */
    public Gender $gender;

    /**
     * Name of the Nomi
     * TSDoc String
     */
    public string $name;

    /**
     * Date of when the Nomi was initially created
     * TSDoc "ISODateString"
     */
    public DateTimeImmutable $created;

    /**
     * The type of relationship you have with this Nomi
     * TSDoc StringEnum
     */
    public RelationshipType $relationshipType;

    /**
     * Nomi constructor.
     *
     * @throws \InvalidArgumentException
     * @throws \DateMalformedStringException
     */
    public function __construct(
        string $uuid,
        string $name,
        string|DateTimeImmutable $created,
        string|Gender $gender,
        string|RelationshipType $relationshipType,
    ) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->created = $created instanceof DateTimeImmutable
            ? $created
            : new DateTimeImmutable($created);
        $this->gender = $gender instanceof Gender
            ? $gender
            : (
                Gender::tryFrom($gender)
                ?? throw new InvalidArgumentException('The provided gender is invalid!')
            );
        $this->relationshipType = $relationshipType instanceof RelationshipType
            ? $relationshipType
            : (
                RelationshipType::tryFrom($relationshipType)
                ?? throw new InvalidArgumentException('The provided relationship type is invalid!')
            );
    }

    /** @inheritDoc */
    public static function make(array $response): static
    {
        return new self(
            uuid: $response['uuid'],
            name: $response['name'],
            created: $response['created'],
            gender: $response['gender'],
            relationshipType: $response['relationshipType'],
        );
    }

    /** @inheritDoc */
    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'created' => $this->created->format(self::ISO8601),
            'gender' => $this->gender->value,
            'relationshipType' => $this->relationshipType->value,
        ];
    }
}
