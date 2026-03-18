<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpStatus;
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

it('creates unauthorized exception', function (): void {
    $exception = NomiException::unauthorized();

    expect($exception)->toBeInstanceOf(NomiException::class)
        ->getCode()->toBe(HttpStatus::UNAUTHORIZED)
        ->and($exception->getMessage())->toContain('Authorization');
});

it('creates invalid API key exception', function (): void {
    $exception = NomiException::invalidApiKey();

    expect($exception)->toBeInstanceOf(NomiException::class)
        ->getCode()->toBe(HttpStatus::BAD_REQUEST)
        ->and($exception->getMessage())->toContain('UUID format');
});

it('creates message character limit exceeded exception', function (): void {
    $exception = NomiException::messageCharacterLimitExceeded();

    expect($exception)->toBeInstanceOf(NomiException::class)
        ->getCode()->toBe(HttpStatus::PAYLOAD_TOO_LARGE)
        ->and($exception->getMessage())->toContain('800');
});

it('can retrieve issues from invalid body exception', function (): void {
    $issues = [
        'messageText' => ['Must not be empty'],
        'nomiUuids' => ['Must contain at least 1 UUID'],
    ];

    $exception = NomiException::invalidBody($issues);

    expect($exception->getIssues())->toBe($issues);
});

it('returns empty array for issues when no data is set', function (): void {
    $exception = NomiException::nomiNotFound();

    expect($exception->getIssues())->toBe([])
        ->and($exception->hasData())->toBeFalse();
});
