# Changelog

All notable changes to `nomiai-php` will be documented in this file.

## 1.1.0 - 2026-03-18

### Added
- Client-side validation for message, room name, and room note length limits
- New `ValidationException` class for validation errors
- Support for API version pinning via constructor parameter
- Authentication error handling (`Unauthorized`, `InvalidAPIKey`)
- `getIssues()` method on `NomiException` for detailed error information
- Missing `ERROR` and `TRANSCRIPTION_ERROR` cases to `RoomStatus` enum
- `MessageCharacterLimitExceeded` error type for room messages
- Comprehensive unit tests for validation logic
- Unit tests for HTTP request handling
- Open-source repository essentials (CONTRIBUTING.md, CODE_OF_CONDUCT.md, SECURITY.md)
- .env.example file for configuration reference

### Fixed
- **CRITICAL**: Changed HTTP request encoding from `FORM_PARAMS` to `JSON` (API requires JSON bodies)
- Fixed typo in `MESSAGE_LENGTH_LIMIT_EXCEEDED` constant (was 'MesageLengthLimitExceeded')
- API version now properly included in base URI instead of hardcoded in each endpoint

### Changed
- All endpoint paths now use relative URIs (removed hardcoded `/v1/` prefix)
- Enhanced exception handling with more specific error types
- Improved docblocks with `@throws` annotations for validation exceptions
