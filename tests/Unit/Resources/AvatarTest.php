<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Resources\Avatar;

it('will throw an exception if the data coming back from the response is wrong', function (): void {
    Avatar::make([]);
})->throws(InvalidArgumentException::class);

it('can be made into an array', function (): void {
    $avatar = Avatar::make([$this->faker->uuid()]); // This would normally be a raw webp.

    expect($avatar->toArray())->toEqual(['avatar' => $avatar->avatar]);
});

it('can be casted into a string', function (): void {
    $uuid = $this->faker->uuid();
    $avatar = Avatar::make([$uuid]);

    expect((string) $avatar)->toEqual($uuid);
});
