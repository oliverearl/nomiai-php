# Contributing to nomiai-php

Thank you for considering contributing to nomiai-php! We welcome contributions from the community.

## How to Contribute

### Reporting Bugs

If you find a bug, please open an issue on GitHub with:
- A clear description of the problem
- Steps to reproduce the issue
- Expected vs. actual behavior
- PHP version and environment details

### Suggesting Features

Feature requests are welcome! Please open an issue describing:
- The feature you'd like to see
- Why it would be useful
- Any implementation ideas you have

### Pull Requests

1. **Fork the repository** and create your branch from `master`
2. **Write tests** for any new functionality
3. **Ensure tests pass** by running `composer test`
4. **Follow code style** by running `composer format`
5. **Update documentation** if you're changing functionality
6. **Write clear commit messages** describing your changes

### Development Setup

```bash
# Clone your fork
git clone https://github.com/YOUR-USERNAME/nomiai-php.git
cd nomiai-php

# Install dependencies
composer install

# Run tests
composer test

# Format code
composer format
```

### Code Standards

- **PHP 8.3+** with strict types (`declare(strict_types=1)`)
- **PER coding style** enforced by Laravel Pint
- **PHPStan level 8** for static analysis
- **Architecture tests** enforce conventions (traits, readonly resources, etc.)
- **Comprehensive tests** for all new features

### Testing Requirements

- All new features must include tests
- Tests should cover both success and error scenarios
- Maintain or improve code coverage
- Follow existing test patterns (see `tests/Feature/` and `tests/Unit/`)

### Architectural Guidelines

- Use **trait-based composition** for new functionality
- Resources must be **readonly** and extend `Resource`
- Follow the **dual-interface pattern** (object + UUID methods)
- Add appropriate **exception handling** with static factory methods

## Code of Conduct

Please be respectful and constructive in all interactions. We're here to build great software together.

## Questions?

Feel free to open an issue for any questions about contributing!


