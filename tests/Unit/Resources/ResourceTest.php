<?php

declare(strict_types=1);

use Nomiai\PhpSdk\Tests\Fixtures\ResourceFake;

it('can be converted into a json representation', function (): void {
   $resource = ResourceFake::make([]);
   $json = json_encode($resource);

   expect($json)->toBeJson('{"foo":"bar"}');
});
