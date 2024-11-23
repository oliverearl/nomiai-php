<?php

declare(strict_types=1);


uses(Nomiai\PhpSdk\Tests\TestCase::class)->in('Feature', 'Unit');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
$dotenv->safeLoad();
