<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Exceptions\NomiException;

it('can store additional data', function (): void {
    $data = ['foo' => 'bar'];
    $exception = NomiException::invalidBody($data);

    expect($exception)
        ->toBeInstanceOf(NomiException::class)
        ->hasData()->toBeTrue()
        ->getData()->toEqual($data);

    // Data can also be changed later.
    $exception->setData(['baz' => 'qux']);

    expect($exception)
        ->hasData()->toBeTrue()
        ->getData()->not()->toEqual($data);
});
