<?php

declare(strict_types=1);

namespace Nomiai\PhpSdk\Tests;

use Faker\Factory;
use Faker\Generator;
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
}
