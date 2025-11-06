# coroq/html

HTML escaping for PHP templates.

## Install

```bash
composer require coroq/html
```

## Quickstart

```php
use function Coroq\Html\h;

<p><?= h('cookies & cream') ?></p>
<!-- Output: <p>cookies &amp; cream</p> -->
```

Use `h()` when echoing - it's safe everywhere.

## How it works

`h()` uses **deferred escaping** - it doesn't escape immediately. Instead, it wraps the value in an `Html` object.

Html objects are like DOM elements - they have a tag, attributes, and children. Children can be strings or other Html objects:

```php
use Coroq\Html\Html;

$label = (new Html())->tag('strong')->append('Home');
$link = (new Html())
  ->tag('a')
  ->attr('href', '/home')
  ->addClass('nav-link')
  ->append($label);  // Html as child

echo $link;
// Output: <a href="/home" class="nav-link"><strong>Home</strong></a>
```

`h()` wraps the value in an Html object **without a tag**, just adding the value as a child:

```php
$wrapped = h('text');  // Returns Html object with no tag, 'text' as child
echo $wrapped;         // Output: text (escaped at render time)
```

**So you can call h() without worrying about double-escaping:**

```php
echo h('&');           // Html wraps '&' → renders as &amp;
echo h(h('&'));        // Html wraps Html → recognizes it's already Html → &amp;

$link = (new Html())->tag('a')->attr('href', '/x');
echo h($link);         // Html wraps Html → <a href="/x"></a>
```

At render time, string children are escaped, Html children render themselves.

## Tag helpers

Since `h()` returns an Html object, you can modify it. But creating elements with `new Html()` is verbose.

We provide helpers to create Html objects more conveniently:

```php
use function Coroq\Html\{h, a, div, ul, li};

// Instead of:
$link = (new Html())->tag('a')->attr('href', '/home')->append('Home');

// Use helpers:
$link = a('/home')->append('Home');

// Always wrap with h() when echoing:
echo h($link);
```

### Conditional markup

```php
use function Coroq\Html\{h, a, span};

<!-- Link if URL exists, otherwise plain text -->
<span><?= h($user->url ? a($user->url)->append($user->name) : $user->name) ?></span>

<!-- Highlight errors -->
<td><?= h($hasError ? span()->addClass('error')->append($value) : $value) ?></td>
```

### Building elements

```php
use function Coroq\Html\{h, div, ul, li};

echo h(div()->addClass('card')->append($content));

echo h(ul()->each($items, fn($el, $item) =>
  $el->append(li()->append($item))
));
```

### Trusted HTML

```php
use function Coroq\Html\{h, noEscape};

echo h(noEscape('<strong>bold</strong>'));
```

Never use `noEscape()` with user input.

## Requirements

- PHP 8.0 or higher
- No dependencies

## Scope

Does: HTML escaping, element generation, conditional markup
Doesn't: Template engines, HTML parsing, DOM manipulation

## Reference

### Core functions

- `h($content)` - escape content, safe to call multiple times
- `noEscape($html)` - mark HTML as safe (dangerous with user input)

### Tag helpers

`div()`, `span()`, `a($href)`, `h1()`-`h6()`, `para()`, `ul()`, `ol()`, `li()`, `table()`, `tr()`, `th()`, `td()`, `form($action, $method)`, `input($type, $name)`, `select($name)`, `option($value, $label)`, `button($type)`, `label($for)`, `textarea($name)`, `img($src)`, `small()`, `br()`, `hr()`

### Fluent methods

- `->append($content)` - add content
- `->attr($name, $value)` - set attribute
- `->addClass($classes)` - add CSS classes
- `->id($id)` - set id
- `->when($condition, $callback)` - conditional modification
- `->each($items, $callback)` - iterate and append

### Helper functions

- `selectOptions($options, $selected)` - populate select
- `externalLink($url)` - external link with target and rel
- `scriptData($name, $value)` - pass PHP data to JavaScript
