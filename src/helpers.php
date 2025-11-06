<?php
declare(strict_types=1);

namespace Coroq\Html;

use Coroq\Html\Html;

/**
 * Wrap content in Html element
 * @param mixed $html
 * @return Html
 */
function h($html): Html
{
  if ($html instanceof Html) {
    return $html;
  }
  return (new Html)->append($html);
}

/**
 * Echo Html content
 * @deprecated Use echo h($html) instead
 * @param mixed $html
 * @return void
 */
function p($html): void
{
  echo h($html);
}

/**
 * Create NoEscape wrapper for raw HTML content
 * WARNING: Content is output as-is without escaping. Risk of XSS if content contains user input.
 * @param mixed $content Raw HTML content to output without escaping
 * @return NoEscape
 */
function noEscape($content): NoEscape
{
  return new NoEscape($content);
}

// Tag creation helpers (return Html)

/**
 * Create a <p> tag
 * @return Html
 */
function para(): Html
{
  return (new Html())->tag('p');
}

/**
 * Create a heading tag (h1-h6)
 * @param int $level Heading level (1-6)
 * @return Html
 */
function heading(int $level = 1): Html
{
  return (new Html())->tag("h$level");
}

/**
 * Create a <h1> tag
 * @return Html
 */
function h1(): Html
{
  return (new Html())->tag('h1');
}

/**
 * Create a <h2> tag
 * @return Html
 */
function h2(): Html
{
  return (new Html())->tag('h2');
}

/**
 * Create a <h3> tag
 * @return Html
 */
function h3(): Html
{
  return (new Html())->tag('h3');
}

/**
 * Create a <h4> tag
 * @return Html
 */
function h4(): Html
{
  return (new Html())->tag('h4');
}

/**
 * Create a <h5> tag
 * @return Html
 */
function h5(): Html
{
  return (new Html())->tag('h5');
}

/**
 * Create a <h6> tag
 * @return Html
 */
function h6(): Html
{
  return (new Html())->tag('h6');
}

/**
 * Create a <div> tag
 * @return Html
 */
function div(): Html
{
  return (new Html())->tag('div');
}

/**
 * Create a <span> tag
 * @return Html
 */
function span(): Html
{
  return (new Html())->tag('span');
}

/**
 * Create a <small> tag
 * @return Html
 */
function small(): Html
{
  return (new Html())->tag('small');
}

/**
 * Create an <a> tag
 * @param string|null $href URL for href attribute
 * @param string|null $target Target attribute value
 * @return Html
 */
function a(?string $href = null, ?string $target = null): Html
{
  return (new Html())
    ->tag('a')
    ->when($href !== null, fn($el) => $el->attr('href', $href))
    ->when($target !== null, fn($el) => $el->attr('target', $target));
}

/**
 * Create a <button> tag
 * @param string $type Button type (button, submit, reset)
 * @return Html
 */
function button(string $type = 'button'): Html
{
  return (new Html())->tag('button')->attr('type', $type);
}

/**
 * Create an <input> tag
 * @param string|null $type Input type
 * @param string|null $name Name attributes
 * @return Html
 */
function input(?string $type = null, ?string $name = null): Html
{
  return (new Html())
    ->tag('input')
    ->when($type !== null, fn($el) => $el->attr('type', $type))
    ->when($name !== null, fn($el) => $el->attr('name', $name));
}

/**
 * Create a <select> tag
 * @param string|null $name Name attributes
 * @return Html
 */
function select(?string $name = null): Html
{
  return (new Html())
    ->tag('select')
    ->when($name !== null, fn($el) => $el->attr('name', $name));
}

/**
 * Create an <option> tag
 * @param string|null $value Value attribute
 * @param string|null $label Option label/text
 * @param bool $selected Whether option is selected
 * @return Html
 */
function option($value = null, $label = null, bool $selected = false): Html
{
  return (new Html())
    ->tag('option')
    ->when($value !== null, fn($el) => $el->attr('value', $value))
    ->when($label !== null, fn($el) => $el->append($label))
    ->when($selected, fn($el) => $el->attr('selected', true));
}

/**
 * Create a <textarea> tag
 * @param string|null $name Name attributes
 * @param int|null $rows Number of visible text lines
 * @param int|null $cols Width in characters
 * @return Html
 */
function textarea(?string $name = null, ?int $rows = null, ?int $cols = null): Html
{
  return (new Html())
    ->tag('textarea')
    ->when($name !== null, fn($el) => $el->attr('name', $name))
    ->when($rows !== null, fn($el) => $el->attr('rows', (string)$rows))
    ->when($cols !== null, fn($el) => $el->attr('cols', (string)$cols));
}

/**
 * Create a <label> tag
 * @param string|null $for For attribute (element id)
 * @return Html
 */
function label(?string $for = null): Html
{
  return (new Html())
    ->tag('label')
    ->when($for !== null, fn($el) => $el->attr('for', $for));
}

/**
 * Create a <form> tag
 * @param string|null $action Form action URL
 * @param string|null $method Form method (get, post)
 * @return Html
 */
function form(?string $action = null, ?string $method = null): Html
{
  return (new Html())
    ->tag('form')
    ->when($action !== null, fn($el) => $el->attr('action', $action))
    ->when($method !== null, fn($el) => $el->attr('method', $method));
}

/**
 * Create a <ul> tag
 * @return Html
 */
function ul(): Html
{
  return (new Html())->tag('ul');
}

/**
 * Create an <ol> tag
 * @return Html
 */
function ol(): Html
{
  return (new Html())->tag('ol');
}

/**
 * Create a <li> tag
 * @return Html
 */
function li(): Html
{
  return (new Html())->tag('li');
}

/**
 * Create a <table> tag
 * @return Html
 */
function table(): Html
{
  return (new Html())->tag('table');
}

/**
 * Create a <thead> tag
 * @return Html
 */
function thead(): Html
{
  return (new Html())->tag('thead');
}

/**
 * Create a <tbody> tag
 * @return Html
 */
function tbody(): Html
{
  return (new Html())->tag('tbody');
}

/**
 * Create a <tr> tag
 * @return Html
 */
function tr(): Html
{
  return (new Html())->tag('tr');
}

/**
 * Create a <th> tag
 * @return Html
 */
function th(): Html
{
  return (new Html())->tag('th');
}

/**
 * Create a <td> tag
 * @return Html
 */
function td(): Html
{
  return (new Html())->tag('td');
}

/**
 * Create an <img> tag
 * @param string|null $src Image source URL
 * @param string|null $alt Alt text
 * @return Html
 */
function img(?string $src = null, ?string $alt = null): Html
{
  return (new Html())
    ->tag('img')
    ->when($src !== null, fn($el) => $el->attr('src', $src))
    ->when($alt !== null, fn($el) => $el->attr('alt', $alt));
}

/**
 * Create an <iframe> tag
 * @param string|null $src Iframe source URL
 * @return Html
 */
function iframe(?string $src = null): Html
{
  return (new Html())
    ->tag('iframe')
    ->when($src !== null, fn($el) => $el->attr('src', $src));
}

/**
 * Create a <time> tag
 * @param int|null $timestamp Unix timestamp
 * @param \DateTimeInterface|null $datetime DateTime object
 * @param string|null $iso8601 ISO 8601 string
 * @param string|null $format Display format (for timestamp or datetime)
 * @return Html
 */
function time(?int $timestamp = null, ?\DateTimeInterface $datetime = null, ?string $iso8601 = null, ?string $format = null): Html
{
  $html = (new Html())->tag('time');

  if ($timestamp !== null) {
    $dt = new \DateTime('@' . $timestamp);
    $dt->setTimezone(new \DateTimeZone('UTC'));
    $html->attr('datetime', $dt->format(\DateTimeInterface::ATOM));
    $content = $format !== null ? $dt->format($format) : $dt->format(\DateTimeInterface::ATOM);
    $html->append($content);
  }
  elseif ($datetime !== null) {
    $html->attr('datetime', $datetime->format(\DateTimeInterface::ATOM));
    $content = $format !== null ? $datetime->format($format) : $datetime->format(\DateTimeInterface::ATOM);
    $html->append($content);
  }
  elseif ($iso8601 !== null) {
    $html->attr('datetime', $iso8601);
    $html->append($iso8601);
  }

  return $html;
}

/**
 * Create a <br> tag
 * @return Html
 */
function br(): Html
{
  return (new Html())->tag('br');
}

/**
 * Create an <hr> tag
 * @return Html
 */
function hr(): Html
{
  return (new Html())->tag('hr');
}

/**
 * Create a <script> tag
 * @param string|null $src Script source URL
 * @param string|null $type Script type attribute
 * @return Html
 */
function script(?string $src = null, ?string $type = null): Html
{
  return (new Html())
    ->tag('script')
    ->when($src !== null, fn($el) => $el->attr('src', $src))
    ->when($type !== null, fn($el) => $el->attr('type', $type));
}

/**
 * Create a script tag with PHP data exported to JavaScript
 * @param string $name JavaScript constant name
 * @param mixed $value PHP value to encode
 * @return Html
 */
function scriptData(string $name, $value): Html
{
  if (!preg_match("#^[a-zA-Z_][a-zA-Z0-9_]*$#", $name)) {
    throw new \InvalidArgumentException();
  }
  $json = json_encode($value);
  if ($json === false) {
    throw new \InvalidArgumentException();
  }
  $base64 = base64_encode($json);
  if ($base64 === false) {
    throw new \RuntimeException();
  }
  $code = "const $name = JSON.parse(atob('$base64'));";
  return script()->append(new \Coroq\Html\NoEscape($code));
}

// Configuration helpers (return Closure for use with apply())

/**
 * Configuration helper for external links
 * Sets href, target="_blank", and rel="noopener noreferrer"
 * @param string $url URL to link to
 * @return \Closure
 */
function externalLink(string $url): \Closure
{
  return fn($el) => $el
    ->attr('href', $url)
    ->attr('target', '_blank')
    ->attr('rel', 'noopener noreferrer');
}

/**
 * Configuration helper for appending select options
 * @param array $options Option values => labels
 * @param array|string $selected Selected value(s)
 * @return \Closure
 */
function selectOptions(array $options, $selected = []): \Closure
{
  return function($el) use ($options, $selected) {
    $selected = (array)$selected;
    foreach ($options as $value => $label) {
      $opt = option($value, $label);
      if (in_array("$value", $selected)) {
        $opt->attr('selected', true);
      }
      $el->append($opt);
    }
    return $el;
  };
}
