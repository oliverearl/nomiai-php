<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();

arch()
    ->expect('Nomiai\PhpSdk')
    ->toUseStrictEquality()
    ->toUseStrictTypes();

arch()
    ->expect('Nomiai\PhpSdk\Actions')
    ->toBeTraits();

arch()
    ->expect('Nomiai\PhpSdk\Constants')
    ->toBeAbstract()
    ->toBeReadonly();

arch()
    ->expect('Nomiai\PhpSdk\Enums')
    ->toBeEnums();

arch()
    ->expect('Nomiai\PhpSdk\Resources')
    ->toBeReadonly();

arch()
    ->expect('Nomiai\PhpSdk\Traits')
    ->toBeTraits();
