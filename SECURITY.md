# Security Policy

## Supported Versions

We release patches for security vulnerabilities in the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability within nomiai-php, please send an email to oliver@oliverearl.co.uk. All security vulnerabilities will be promptly addressed.

Please include the following information in your report:

- Type of vulnerability
- Full paths of source file(s) related to the vulnerability
- The location of the affected source code (tag/branch/commit or direct URL)
- Any special configuration required to reproduce the issue
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the issue, including how an attacker might exploit it

## Security Best Practices

When using this SDK:

1. **Keep your API token secure** - Never commit tokens to version control
2. **Use environment variables** - Store sensitive credentials in `.env` files
3. **Validate user input** - Always validate data before sending to the API
4. **Keep dependencies updated** - Regularly run `composer update` to get security patches
5. **Use HTTPS** - The SDK defaults to HTTPS; don't override with HTTP endpoints

## Disclosure Policy

When we receive a security bug report, we will:

1. Confirm the problem and determine the affected versions
2. Audit code to find any similar problems
3. Prepare fixes for all supported versions
4. Release new versions as soon as possible

## Comments on this Policy

If you have suggestions on how this process could be improved, please submit a pull request.

