<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\ErrorResponse;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Exceptions\NomiException;

it('can produce the correct exception based on the error received from the response', function (string $description, string $error): void {
    /*
     * For the sake of this test, we're going to use one specific API call, and pretend all errors
     * can be returned from it. They're all going to return 500, also, since that's not important.
     */
    $api = $this->dummy(
        '/v1/nomis',
        HttpMethod::GET,
        HttpStatus::INTERNAL_SERVER_ERROR,
        [
            'error' => [
                'type' => $error,
            ],
        ],
    );

    $api->getNomis();
})->with(function (): array {
    $reflection = new ReflectionClass(ErrorResponse::class);
    $errors = $reflection->getConstants();

    // Add a bogus one to test its fallback capabilities:
    $errors['COMPLETELY_UNKNOWN_ERROR'] = 'UnknownError';

    // Human-readable keys for test output:
    $improvedKeys = array_map(
        static fn(string $key): string => ucfirst(strtolower(str_replace('_', ' ', $key))),
        array_keys($errors)
    );

    return array_map(
        fn(string $error, string $message): array => [$message, $error],
        array_values($errors),
        $improvedKeys,
    );
})->throws(NomiException::class);
