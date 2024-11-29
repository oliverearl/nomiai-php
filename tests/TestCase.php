<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Tests;

use Faker\Factory;
use Faker\Generator;
use Nomiai\PhpSdk\Enums\Gender;
use Nomiai\PhpSdk\Enums\RelationshipType;
use Nomiai\PhpSdk\Resources\Nomi;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Faker generator used for producing bogus data.
     */
    protected readonly Generator $faker;

    /** @inheritdoc  */
    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    /**
     * Generate a Nomi for testing purposes.
     */
    public function nomi(): Nomi
    {
        return Nomi::make([
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'created' => $this->faker->date(Nomi::ISO8601),
            'gender' => ($this->faker->randomElement(Gender::cases()))->value,
            'relationshipType' => ($this->faker->randomElement(RelationshipType::cases()))->value,
        ]);
    }
}
