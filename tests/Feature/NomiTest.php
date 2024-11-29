<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Resources\Nomi;

describe('nomis', function (): void {
    describe('index', function (): void {
        it('can retrieve all nomis associated with an account', function (): void {
            $nomis = [
                $this->nomi()->toArray(),
                $this->nomi()->toArray(),
            ];

            $api = $this->dummy(
                uri: '/v1/nomis',
                method: HttpMethod::GET,
                status: HttpStatus::OK,
                body: ['nomis' => $nomis],
            );

            $retrievedNomis = $api->getNomis();

            foreach ($retrievedNomis as $index => $retrievedNomi) {
                expect($retrievedNomi)
                    ->toBeInstanceOf(Nomi::class)
                    ->toArray()->toEqual($nomis[$index]);
            }
        });
    });

    describe('show', function (): void {
        it('can retrieve a nomi associated with an account', function (): void {
            $nomi = $this->nomi();
            $id = $nomi->uuid;

            $api = $this->dummy(
                uri: "/v1/nomis/{$id}",
                method: HttpMethod::GET,
                status: HttpStatus::OK,
                body: $nomi->toArray(),
            );

            $retrievedNomi = $api->getNomi($id);

            expect($retrievedNomi)
                ->toBeInstanceOf(Nomi::class)
                ->uuid->toEqual($id)
                ->toArray()->toEqual($nomi->toArray());
        });
    });
});
