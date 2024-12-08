<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Requests\RoomRequest;
use Nomiai\PhpSdk\Resources\Nomi;

it('can create a room request and be converted into an array', function (): void {
    $name = $this->faker->word();
    $note = $this->faker->sentence();
    $bcEnabled = $this->faker->boolean();
    $nomiUuids = [
        $this->faker->uuid(),
        $this->faker->uuid(),
    ];

    $request = new RoomRequest($name, $note, $bcEnabled, $nomiUuids);

    expect($request)
        ->toBeInstanceOf(RoomRequest::class)
        ->name->toEqual($name)
        ->note->toEqual($note)
        ->backchannelingEnabled->toEqual($bcEnabled)
        ->nomiUuids->toEqual($nomiUuids)
        ->toArray()->toEqual([
            'name' => $name,
            'note' => $note,
            'backchannelingEnabled' => $bcEnabled,
            'nomiUuids' => $nomiUuids,
        ]);
});

it('can selectively include parameters', function (): void {
    $name = $this->faker->word();
    $request = new RoomRequest($name);

    expect($request)
        ->name->toEqual($name)
        ->note->toBeNull()
        ->backchannelingEnabled->toBeNull()
        ->nomiUuids->toBeNull();
});

it('will automatically cast arrays of nomis into a string[] of their uuids', function (): void {
    $nomis = [$this->nomi(), $this->nomi()];
    $request = new RoomRequest(nomiUuids: $nomis);

    expect($request)
        ->toBeInstanceOf(RoomRequest::class)
        ->nomiUuids->toEqual($nomis);

    $data = $request->toArray();

    expect($data['nomiUuids'])
        ->toEqual(array_map(fn(Nomi $n): string => $n->uuid, $nomis));
});

it('can be converted into a json representation', function (): void {
    $name = $this->faker->word();
    $request = new RoomRequest(name: $name);
    $json = json_encode($request);

    expect($json)
        ->toBeJson()
        ->toContain($name);
});
