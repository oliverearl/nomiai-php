# Nomi.ai PHP Library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/oliverearl/nomiai-php.svg?style=flat-square)](https://packagist.org/packages/oliverearl/nomiai-php)
[![Tests](https://img.shields.io/github/actions/workflow/status/oliverearl/nomiai-php/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/oliverearl/nomiai-php/actions/workflows/run-tests.yml)
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

### Retrieving an avatar

You can retrieve the avatar from a Nomi with either a Nomi object, or their corresponding UUID.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/
/** @var \Nomiai\PhpSdk\Resources\Nomi $nomi **/

$avatar = $sdk->getAvatarFromNomi($nomi);

// Or via the UUID:
$avatar = $sdk->getAvatar($nomi->uuid);

/*
 * Avatar is an object that might get more functionality down the line. For now, 
 * to get the underlying .webp image, access the avatar property.
 */
$webp = $avatar->avatar;

// You can also retrieve the underlying image by casting the object to a string.
$image = (string) $avatar;
```

### Rooms

#### Retrieving rooms

You can retrieve a numerically-indexed array of rooms associated with your account. Just like with Nomis, if you know
the UUID of the room, you can also retrieve this individually.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/

/** @var array<int, \Nomiai\PhpSdk\Resources\Room> $myRooms */
$myRooms = $sdk->getRooms();

/** @var \Nomiai\PhpSdk\Resources\Room $myFavouriteRoom */
$myFavouriteRoom = $sdk->getRoom(id: 'Your room UUID here');
```

#### Creating a room for your Nomis

Creating a room for Nomis can be done with *either* an array of Nomi objects, or an array containing their respective
UUIDs. A `RoomRequest` object exists to help you with this process, or you can use an associative array.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/

// Get all Nomis:
/** @var array<int, \Nomiai\PhpSdk\Resources\Nomi> $nomis **/
$nomis = $sdk->getNomis();

$request = new RoomRequest(
    name: 'Nomi HQ',
    note: 'The coolest place for Nomis to hang out',
    backchannelingEnabled: true,
    nomiUuids: $nomis,
);

/** @var \Nomiai\PhpSdk\Resources\Room $room */
$room = $sdk->createRoom($request);
```

#### Updating and deleting rooms

Updating an existing room can also be done with a `RoomRequest` object, or an associative array containing the values
you wish to change. Whatever you choose, it's a good idea not to include fields you don't want to update.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/
/** @var \Nomiai\PhpSdk\Resources\Room $room **/

$request = [
    'name' => 'The New Nomi HQ!',
];

$room = $sdk->updateRoom($room, $request);

// You can also update a room by its UUID, for example if you don't have the full object.
$room = $sdk->updateRoomById($room->uuid, $request);
```

Deleting a room is straightforward. These methods will simply return `true` as they will throw an appropriate
exception if something goes wrong.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/
/** @var \Nomiai\PhpSdk\Resources\Room $room **/

$sdk->deleteRoom($room);

// You can also delete it by its UUID
$sdk->deleteRoomById($room->uuid);
```

#### Messaging within rooms

Sending a message into a room is easy. Be aware rooms do take time to initialise, so don't send a message to them
immediately after creation. The message that was sent is returned as the result.


```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/
/** @var \Nomiai\PhpSdk\Resources\Room $room **/

$message = 'Hello friends!';
$conversation = $sdk->sendMessageToRoom($room, $message);

// Or via UUID:
$conversation = $sdk->sendMessageToRoomById($room->uuid, $message);
```

Nomis can be prompted to send a message to a room. For more information about this, please check the 
[Nomi.ai](https://www.nomi.ai) website.

```php
/** @var \Nomiai\PhpSdk\NomiAI $sdk **/
/** @var \Nomiai\PhpSdk\Resources\Nomi $nomi **/
/** @var \Nomiai\PhpSdk\Resources\Room $room **/

$message = $sdk->requestNomiToMessageRoom($nomi, $room);

// If you only have the Nomi UUID, and/or the room UUID, you have options:
$message = $sdk->requestNomiByIdToMessageRoom($nomi->uuid, $room);
$message = $sdk->requestNomiByIdToMessageRoomById($nomi->uuid, $room->uuid);
```

## Functionality

The library is under active development. Complete feature availability is planned for the first major release.

| Nomi.ai API Functionality          | Implemented | Release      |
|------------------------------------|-------------|:-------------|
| Retrieve and view Nomis            | Yes         | v0.1.2-alpha |
| Send and receive messages to Nomis | Yes         | v0.1.3-alpha |
| Retrieve Nomi avatars              | Yes         | v0.1.6-alpha |
| Retrieve rooms                     | Yes         | v0.2.0-alpha |
| Create rooms                       | Yes         | v0.2.0-alpha |
| Send messages into rooms           | Yes         | v0.2.0-alpha |
| Delete rooms                       | Yes         | v0.2.0-alpha |

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
