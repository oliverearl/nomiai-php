<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Constants\HttpMethod;
use Nomiai\PhpSdk\Constants\HttpStatus;
use Nomiai\PhpSdk\Resources\Avatar;

describe('avatars', function (): void {
    it('can retrieve an avatar belonging to a nomi', function (): void {
        $nomi = $this->nomi();
        $image = 'Definitely a 1px webp image';

        $api = $this->dummy(
            uri: "/v1/nomis/{$nomi->uuid}/avatar",
            method: HttpMethod::GET,
            status: HttpStatus::OK,
            body: $image,
            headers: [
                'Content-Type' => 'image/webp',
            ],
        );

        expect($api->getAvatar($nomi->uuid))
            ->toBeInstanceOf(Avatar::class)
            ->avatar->toContain($image)
            ->and($api->getAvatarFromNomi($nomi))
            ->toBeInstanceOf(Avatar::class)
            ->avatar->toContain($image);
    });
});
