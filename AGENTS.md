# AI Agent Guide for nomiai-php

## Project Overview
PHP SDK for the Nomi.ai REST API—a companionship AI platform. The library uses a trait-based architecture where the main `NomiAI` class composes functionality from action traits (`ManagesChats`, `ManagesNomis`, `ManagesAvatars`, `ManagesRooms`) and a shared HTTP request trait (`MakesHttpRequests`).

**Language**: PHP 8.3+ with strict types (`declare(strict_types=1)` in every file)  
**HTTP Client**: Guzzle (dev dependency; abstracted via PSR `ClientInterface`)  
**Testing**: PestPHP with architecture tests enforcing conventions  
**Code Style**: Laravel Pint using PER preset (`pint.json`)  
**Static Analysis**: PHPStan level 8 (`phpstan.neon`)

## Architecture Patterns

### Trait-Based Composition
The SDK composes behavior via traits instead of inheritance:
- `src/NomiAI.php` uses 4 action traits + 1 HTTP trait
- Each action trait (e.g., `ManagesChats`) expects `MakesHttpRequests` via `@mixin` annotation
- Convention: Traits in `Actions/` namespace must be traits (enforced by `ArchitectureTest.php`)

### Resource Pattern
All API responses map to readonly resources extending `src/Resources/Resource.php`:
- Must implement `static make(array $response): static` for hydration from API responses
- Must implement `toArray(): array` for serialization
- All resource classes are readonly (enforced by architecture tests)
- Example: `Nomi::make($response)` in `ManagesNomis::getNomi()`

### Dual-Interface Methods
API methods accept both resource objects and UUIDs for flexibility:
```php
// Both work:
$sdk->sendMessageToNomi($nomi, 'Hello');        // Takes Nomi object
$sdk->sendMessage($nomi->uuid, 'Hello');        // Takes UUID string
```
Pattern used in: chats, rooms, avatars. The object method internally calls the UUID method.

### Request Objects
Complex API payloads use dedicated request classes (e.g., `RoomRequest`):
- Implements `JsonSerializable` with `toArray()`
- Nullable properties—`array_filter()` removes nulls before sending
- Accepts flexible types: `nomiUuids` accepts `array<string|Nomi>`, internally maps to UUIDs
- Methods accept `array|RequestObject` for convenience

## Development Workflows

### Running Tests
```bash
composer test                 # Run all tests via Pest
composer test-coverage        # With coverage (requires Xdebug)
vendor/bin/pest --filter=Chat # Run specific test suite
```

### Code Formatting
```bash
composer format              # Auto-format with Pint (PER preset)
```

### Test Structure
- `tests/TestCase.php` provides `dummy()` helper for mocking Guzzle responses
- Uses `tomb1n0/guzzle-mock-handler` for HTTP mocking in tests
- Faker available as `$this->faker` in all test cases
- Architecture tests enforce: strict types, strict equality, readonly resources, traits-as-traits

### Environment
Tests load `.env` via `tests/Pest.php` using `vlucas/phpdotenv`. Optional token: check `TestCase` for real API usage patterns.

## Critical Conventions

### Exception Handling
All API errors throw `NomiException` with static factory methods:
- `NomiException::rateLimitExceeded()`, `NomiException::nomiNotFound()`, etc.
- HTTP error responses decoded in `MakesHttpRequests::handleRequestError()` using match statement
- Error types map from `Constants/ErrorResponse.php` constants

### DateTime Handling
Nomi.ai returns ISO8601 with milliseconds: `Y-m-d\TH:i:s.vT`
- Constant defined: `Resource::ISO8601`
- All date fields use `DateTimeImmutable` (readonly pattern)
- Constructor accepts `string|DateTimeImmutable` for flexibility

### Enums
API string values map to backed enums:
- `Gender`, `RelationshipType`, `RoomStatus` in `Enums/`
- Resource constructors use `tryFrom()` with fallback to `InvalidArgumentException`
- Example: `Nomi` constructor validates gender/relationship enums

### HTTP Configuration
`NomiAI` constructor auto-configures Guzzle client:
- Base URI: `NomiAI::DEFAULT_ENDPOINT` (`https://api.nomi.ai`)
- Headers: Authorization (API token), Accept/Content-Type (JSON), User-Agent
- `RequestOptions::HTTP_ERRORS => false` (manual error handling via `isSuccessful()`)

## Key Files
- `src/NomiAI.php`: Main SDK entry point with trait composition
- `src/Traits/MakesHttpRequests.php`: HTTP abstraction layer (GET/POST/PUT/PATCH/DELETE)
- `src/Resources/Resource.php`: Base resource contract
- `tests/TestCase.php`: Test utilities and Guzzle mocking helper
- `tests/Feature/ArchitectureTest.php`: Enforces architectural conventions

## Adding Features
1. **New API endpoint**: Add method to appropriate trait in `Actions/` (or create new trait and use in `NomiAI`)
2. **New resource**: Extend `Resource`, implement `make()` and `toArray()`, mark readonly
3. **New error type**: Add constant to `ErrorResponse`, add match case in `MakesHttpRequests::handleRequestError()`, add static factory to `NomiException`
4. **Tests**: Use `$this->dummy()` for mocking HTTP responses, follow existing Feature test patterns

## External Dependencies
- **Guzzle**: HTTP client (injected via constructor for testability)
- **Laravel Pint**: Code formatter (PER preset, not Laravel-specific)
- **PestPHP**: Test framework with arch plugin for architectural rules
- **Faker**: Test data generation (available via `$this->faker`)


