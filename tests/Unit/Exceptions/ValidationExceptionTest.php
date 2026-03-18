<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Exceptions\ValidationException;

describe('ValidationException', function (): void {
    it('creates exception for nomi message too long', function (): void {
        $exception = ValidationException::nomiMessageTooLong(1000, 800);

        expect($exception)->toBeInstanceOf(ValidationException::class)
            ->getMessage()->toContain('1000 characters')
            ->and($exception->getMessage())->toContain('800 characters');
    });

    it('creates exception for room message too long', function (): void {
        $exception = ValidationException::roomMessageTooLong(900, 800);

        expect($exception)->toBeInstanceOf(ValidationException::class)
            ->getMessage()->toContain('900 characters')
            ->and($exception->getMessage())->toContain('800 characters');
    });

    it('creates exception for room name too long', function (): void {
        $exception = ValidationException::roomNameTooLong(150, 100);

        expect($exception)->toBeInstanceOf(ValidationException::class)
            ->getMessage()->toContain('150 characters')
            ->and($exception->getMessage())->toContain('100 characters');
    });

    it('creates exception for room note too long', function (): void {
        $exception = ValidationException::roomNoteTooLong(1500, 1000);

        expect($exception)->toBeInstanceOf(ValidationException::class)
            ->getMessage()->toContain('1500 characters')
            ->and($exception->getMessage())->toContain('1000 characters');
    });
});
