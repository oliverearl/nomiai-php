<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Resources;

use DateTimeImmutable;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use RuntimeException;

class Nomi
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
            : (
                DateTimeImmutable::createFromFormat(DateTimeImmutable::ATOM, $created)
                ?: throw new RuntimeException('The provided creation date is invalid!')
            );
        $this->gender = $gender instanceof Gender
            ? $gender
            : (
                Gender::tryFrom($gender)
                ?? throw new RuntimeException('The provided gender is invalid!')
            );
        $this->relationshipType = $relationshipType instanceof RelationshipType
            ? $relationshipType
            : (
                RelationshipType::tryFrom($relationshipType)
                ?? throw new RuntimeException('The provided relationship type is invalid!')
            );
    }

    /**
     * Return a new Nomi from an API representation.
     *
     * @param array<string, mixed> $response
     */
    public static function make(array $response): self
    {
        return new self(
            uuid: $response['uuid'],
            name: $response['name'],
            created: $response['created'],
            gender: $response['gender'],
            relationshipType: $response['relationshipType'],
        );
    }
}
