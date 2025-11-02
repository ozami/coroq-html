# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.3.0] - 2025-11-02

### Breaking Changes
- **Renamed `call()` method to `apply()`** - Update your code: `->call(fn)` becomes `->apply(fn)`

### Added
- `data($name, $value)` - Set data attributes
- `wrap($wrapper)` - Wrap element with another Html element
- `when($condition, $callback)` - Conditionally execute callback
- `each($items, $callback)` - Iterate over items
- Type declarations for all methods (return types and parameter types)
- `declare(strict_types=1)` to all source files
- Helper functions for common HTML elements (h1-h6, div, span, a, form elements, etc.)
- Configuration helpers: `externalLink()`, `selectOptions()`

### Changed
- Improved `apply()` method implementation (previously `call()`)
- Fixed type of `Input::chacked()` to `checked()`.
