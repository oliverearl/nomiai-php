<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use Nomiai\PhpSdk\Resources\Nomi;

it('will throw an exception if the gender is invalid', function (): void {
    new Nomi(
        uuid: $this->faker->uuid(),
        name: $this->faker->name(),
        created: new DateTimeImmutable(),
        gender: 'not a valid gender',
        relationshipType: $this->faker->randomElement(RelationshipType::cases()),
    );
})->throws(InvalidArgumentException::class);

it('will throw an exception if the relationship type is invalid', function (): void {
    new Nomi(
        uuid: $this->faker->uuid(),
        name: $this->faker->name(),
        created: new DateTimeImmutable(),
        gender:  $this->faker->randomElement(Gender::cases()),
        relationshipType: 'not a valid relationship type',
    );
})->throws(InvalidArgumentException::class);

it('can be made into an array', function (): void {
    $nomi = new Nomi(
        uuid: $this->faker->uuid(),
        name: $this->faker->name(),
        created: new DateTimeImmutable(),
        gender:  $this->faker->randomElement(Gender::cases()),
        relationshipType: $this->faker->randomElement(RelationshipType::cases()),
    );

    expect($nomi->toArray())
        ->toEqual([
            'uuid' => $nomi->uuid,
            'name' => $nomi->name,
            'created' => $nomi->created->format(Nomi::ISO8601),
            'gender' => $nomi->gender->value,
            'relationshipType' => $nomi->relationshipType->value,
        ]);
});
