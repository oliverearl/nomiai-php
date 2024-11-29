<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\NomiAI;
use Nomiai\PhpSdk\Resources\Nomi;
use Tomb1n0\GuzzleMockHandler\GuzzleMockHandler;
use Tomb1n0\GuzzleMockHandler\GuzzleMockResponse;

describe('nomis', function (): void {
    describe('index', function (): void {
        it('can retrieve all nomis associated with an account', function (): void {
            $nomis = [
                $this->nomi()->toArray(),
                $this->nomi()->toArray(),
            ];

            $handler = new GuzzleMockHandler();
            $response = new GuzzleMockResponse('/v1/nomis');
            $response
                ->withMethod(HttpMethod::GET)
                ->withStatus(HttpStatus::OK)
                ->withBody(['nomis' => $nomis]);
            $handler->expect($response);

            $api = new NomiAI('', '', new Client(['handler' => HandlerStack::create($handler)]));
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

            $handler = new GuzzleMockHandler();
            $response = new GuzzleMockResponse("/v1/nomis/{$id}");
            $response
                ->withMethod(HttpMethod::GET)
                ->withStatus(HttpStatus::OK)
                ->withBody($nomi->toArray());
            $handler->expect($response);

            $api = new NomiAI('', '', new Client(['handler' => HandlerStack::create($handler)]));
            $retrievedNomi = $api->getNomi($id);

            expect($retrievedNomi)
                ->toBeInstanceOf(Nomi::class)
                ->uuid->toEqual($id)
                ->toArray()->toEqual($nomi->toArray());
        });
    });
});
