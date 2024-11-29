<?php

declare(strict_types=1);

use Nomiai\PhpSdk\NomiAI;

it('can return its token', function (): void {
    $token = $this->faker->uuid();
    $nomi = new NomiAI($token);

    expect($nomi->token())->toEqual($token);
});

it('can return its endpoint', function (): void {
    $nomi = new NomiAI($this->faker->uuid());

    expect($nomi->endpoint())->toEqual(NomiAI::DEFAULT_ENDPOINT);
});

it('will throw an exception if an empty token is provided', function (): void {
    new Nomiai('');
})->throws(InvalidArgumentException::class);
