# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.4.0] - 2025-11-06

### Breaking Changes
- **PHP 8.0+ required** - Minimum PHP version increased from 7.2 to 8.0
- **Removed Tag classes** - Removed `Tag\A`, `Tag\Br`, `Tag\Input`, `Tag\Option`, `Tag\Script`, `Tag\Select` classes. Use helper functions instead (e.g., `a()`, `br()`, `input()`)
- **Removed `nl2br()` helper** - No longer provided
- **Removed `rows()` method** - No longer available on Html class

### Added
- `noEscape($content)` - Wrap raw HTML content for output without escaping
- `scriptData($name, $value)` - Create script tag with PHP data exported to JavaScript
- Typed properties for all class properties (`Html` and `NoEscape`)
- Expanded helper functions with more HTML elements and attributes support

### Changed
- Moved `NoEscape` class from `Tag\NoEscape` to `NoEscape` (root namespace)
- Changed all properties in `Html` and `NoEscape` to private with type declarations
- Improved helper functions with better parameter handling and conditional attributes

### Deprecated
- `p($html)` - Use `echo h($html)` instead

## [0.3.0] - 2025-11-02

### Breaking Changes
- **Renamed `call()` method to `apply()`** - Update your code: `->call(fn)` becomes `->apply(fn)`

### Added
- `data($name, $value)` - Set data attributes
- `wrap($wrapper)` - Wrap element with another Html element
- `when($condition, $callback)` - Conditionally execute callback
- `each($items, $callback)` - Iterate over items
- Type declarations for all methods (return types and parameter types)
- Typed properties for all class properties (Html and NoEscape)
- `declare(strict_types=1)` to all source files
- Helper functions for common HTML elements (h1-h6, div, span, a, form elements, etc.)
- Configuration helpers: `externalLink()`, `selectOptions()`
- Comprehensive docblock descriptions for all methods in Html class

### Changed
- Improved `apply()` method implementation (previously `call()`)
- Fixed type of `Input::chacked()` to `checked()`
- Completely rewrote README.md with focus on deferred escaping concept and clearer mental model
