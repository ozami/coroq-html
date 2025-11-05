# coroq/html

Safe HTML escaping and generation for PHP templates.

## Requirements

- PHP 8.0 or higher
- No external dependencies

## Core concept

**Always use `h()` before echo.** It's safe to call multiple times:

```php
use function Coroq\Html\h;

echo h('&');           // &amp;
echo h(h('&'));        // &amp; (not double-escaped)
echo h(h(h('&')));     // &amp; (still not double-escaped)
```

`h()` wraps content in an Html object that escapes strings but passes through Html objects unchanged. This prevents double-escaping and enables mixing strings with Html objects.

**The problem it solves:**

```php
<!-- Using htmlspecialchars() - breaks with Html objects -->
<span><?= htmlspecialchars($user->profileUrl ? a($user->profileUrl)->append($user->name) : $user->name) ?></span>
<!-- Output: &lt;a href="/profile/123"&gt;John&lt;/a&gt; (broken) -->

<!-- Using h() - works with both strings and Html objects -->
<span><?= h($user->profileUrl ? a($user->profileUrl)->append($user->name) : $user->name) ?></span>
<!-- Output: <a href="/profile/123">John</a> (correct) -->
```

## Usage

### Escaping in templates

```php
use function Coroq\Html\h;

<!-- Basic escaping -->
<p><?= h($userInput) ?></p>

<!-- Works with any value -->
<div><?= h($product->name) ?></div>
<span><?= h($count) ?></span>
```

### Conditional markup

Add markup conditionally without breaking the template flow:

```php
use function Coroq\Html\{h, a, span};

<!-- Link usernames that have profiles -->
<span><?= h($user->profileUrl ? a($user->profileUrl)->append($user->name) : $user->name) ?></span>

<!-- Highlight errors -->
<td><?= h($hasError ? span()->addClass('error')->append($value) : $value) ?></td>

<!-- Badge for special status -->
<?= h($isPremium ? span()->addClass('badge')->append('Premium') : null) ?>
```

### Building elements

```php
use function Coroq\Html\{div, span, a};

$html = div()
  ->addClass('container')
  ->append(
    span()->addClass('label')->append('Name:')
  )
  ->append(' ')
  ->append(
    a('/profile')->append($user->name)
  );

echo $html;
// <div class="container"><span class="label">Name:</span> <a href="/profile">John</a></div>
```

### Lists and iteration

```php
use function Coroq\Html\{ul, li};

$items = ['Apple', 'Banana', 'Orange'];

echo ul()->each($items, fn($el, $item) =>
  $el->append(li()->append($item))
);
```

### Conditional rendering

```php
$el = div()
  ->append('Base content')
  ->when($showExtra, fn($el) =>
    $el->append(' Extra content')
  );
```

### Select options

```php
use function Coroq\Html\{select, selectOptions};

$options = ['us' => 'United States', 'uk' => 'United Kingdom'];

echo select('country')->apply(selectOptions($options, 'uk'));
```

### Unsafe content

By default, all content is escaped. Use `noEscape()` only for trusted HTML:

```php
use function Coroq\Html\{h, noEscape};

echo h('<script>alert("xss")</script>');
// &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;

echo h(noEscape('<strong>bold</strong>'));
// <strong>bold</strong>
```

## Key features

**No double-escaping**: `h()` is idempotent - safe to call multiple times on the same value.

**Unified interface**: Works with strings, Html objects, numbers, null - always returns safe output.

**Auto-escaping**: All content is escaped by default unless explicitly marked with `noEscape()`.

**Fluent interface**: Methods return `$this` for chaining.

**Composition**: Use `apply()`, `when()`, `each()` with closures for flexible patterns.

## What this library does

- Escape values safely in templates
- Provide consistent interface for strings and Html objects
- Generate HTML programmatically with fluent interface
- Support conditional and iterative rendering

## What this library does not do

- Template engines (use with your existing PHP templates)
- HTML parsing or manipulation
- DOM traversal or querying
- CSS or JavaScript generation

## Available helpers

**Core**: `h()` - wrap/escape content, `noEscape()` - mark content as safe (use with caution)

**Tags**: `div()`, `span()`, `a()`, `h1()`-`h6()`, `para()`, `ul()`, `ol()`, `li()`, `table()`, `thead()`, `tbody()`, `tr()`, `th()`, `td()`, `form()`, `input()`, `select()`, `option()`, `button()`, `label()`, `textarea()`, `img()`, `iframe()`, `time()`, `br()`, `hr()`, `script()`, `small()`

**Configuration**: `externalLink()`, `selectOptions()`, `scriptData()`

**Deprecated**: `p()` - use `h()` instead
