# Nomi.ai PHP Library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oliverearl/nomiai-php.svg?style=flat-square)](https://packagist.org/packages/oliverearl/nomiai-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/oliverearl/nomiai-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/oliverearl/nomiai-php/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/oliverearl/nomiai-php.svg?style=flat-square)](https://packagist.org/packages/oliverearl/nomiai-php)

This is a lightweight, PHP library for interacting with the Nomi.ai REST API. [Nomi AI](https://www.nomi.ai) is a
companionship application that uses artificial intelligence.

The only prerequisite is at least PHP 8.3 with the JSON extension. You'll need a HTTP client too, preferably Guzzle. If
you're using a popular framework, you should be good to go.

If you're using Laravel, install the [Laravel-specific library](https://github.com/oliverearl/nomiai-php-laravel) 
instead.

## Installation

You can install the package via Composer:

```bash
composer require oliverearl/nomiai-php
```

## Usage

### Quickstart

After installing the library, getting started is easy:

```php
$sdk = new \Nomiai\PhpSdk\NomiAI(
    token: $yourNomiAiApiKey, // Find it in the Integrations tab of the Nomi.ai app!
    // You can also provide a custom endpoint and HTTP Client as additional arguments.
);

$nomis = $sdk->getNomis();
$conversation = $sdk->sendMessageToNomi($nomis[0], 'Hello!');
```

### Retrieving Nomis

A numerically-indexed array of Nomis associated with an account can be returned using the library. If you know your
Nomi's UUID, you can also retrieve their data individually.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/

// Get all Nomis:
/** @var array<int, \Nomiai\PhpSdk\Resources\Nomi> $nomis **/
$nomis = $sdk->getNomis();

// Grab a specific Nomi:
/** @var \Nomiai\PhpSdk\Resources\Nomi $nomi **/
$nomi = $sdk->getNomi(id: 'Your Nomi UUID here');
```

### Chatting with Nomis

Once you have a Nomi you want to chat with, or if you know their UUID, sending a message is easy. If all goes well, you 
will end up with a `MessageSet` object containing both your message and the corresponding reply.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/
/** @var \Nomiai\PhpSdk\Resources\Nomi $nomi **/

// Send a message directly to a Nomi
$conversation = $sdk->sendMessageToNomi($nomi, 'Hello!');

// Or to their UUID!
$conversation = $sdk->sendMessage($nomi->uuid, 'World!'); 
```

## Functionality

The library is under active development. Complete feature availability is planned for the first major release.

| Nomi.ai API Functionality          | Implemented | Release      |
|------------------------------------|-------------|:-------------|
| Retrieve and view Nomis            | Yes         | v0.1.2-alpha |
| Send and receive messages to Nomis | Yes         | v0.1.3-alpha |
| Retrieve Nomi avatars              | Yes         | v0.1.5-alpha |
| Retrieve rooms                     | No          |              |
| Create rooms                       | No          |              |
| Send messages into rooms           | No          |              |
| Delete rooms                       | No          |              |


For more information regarding the Nomi.ai API, please check the documentation available 
[here](https://api.nomi.ai/docs/reference).

## Testing

Tests are run using the [Pest](https://pestphp.com/) testing framework. You can run the suite like so:

```bash
composer test
```

## Code Style

Laravel Pint is used to maintain the PER coding style. The linter can be run using:

```bash
composer format
```

There are Pest architecture tests that also attempt to maintain certain conventions, including the use of strict
typing where possible.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Your contributions are warmly welcomed! Anything from documentation, to optimisations, and additional tests. Pull
requests must pass the existing test suite and conform to the required code style.

For new functionality, adequate tests must be included!

## Credits

- [Oliver Earl](https://github.com/oliverearl)
- [Nomi.ai](https://www.nomi.ai)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
