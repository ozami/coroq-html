<?php
namespace Coroq\Html;

use Coroq\Html\Html;

// Tag creation helpers (return Html)

/**
 * Create a <p> tag
 * @return Html
 */
function para()
{
  return (new Html())->tag('p');
}

/**
 * Create a heading tag (h1-h6)
 * @param int $level Heading level (1-6)
 * @return Html
 */
function heading($level = 1)
{
  return (new Html())->tag("h$level");
}

/**
 * Create a <h1> tag
 * @return Html
 */
function h1()
{
  return (new Html())->tag('h1');
}

/**
 * Create a <h2> tag
 * @return Html
 */
function h2()
{
  return (new Html())->tag('h2');
}

/**
 * Create a <h3> tag
 * @return Html
 */
function h3()
{
  return (new Html())->tag('h3');
}

/**
 * Create a <h4> tag
 * @return Html
 */
function h4()
{
  return (new Html())->tag('h4');
}

/**
 * Create a <h5> tag
 * @return Html
 */
function h5()
{
  return (new Html())->tag('h5');
}

/**
 * Create a <h6> tag
 * @return Html
 */
function h6()
{
  return (new Html())->tag('h6');
}

/**
 * Create a <div> tag
 * @return Html
 */
function div()
{
  return (new Html())->tag('div');
}

/**
 * Create a <span> tag
 * @return Html
 */
function span()
{
  return (new Html())->tag('span');
}

/**
 * Create an <a> tag
 * @param string|null $href URL for href attribute
 * @param string|null $target Target attribute value
 * @return Html
 */
function a($href = null, $target = null)
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
function button($type = 'button')
{
  return (new Html())->tag('button')->attr('type', $type);
}

/**
 * Create an <input> tag
 * @param string $type Input type
 * @param string|null $name Name and id attributes
 * @return Html
 */
function input($type = 'text', $name = null)
{
  return (new Html())
    ->tag('input')
    ->attr('type', $type)
    ->when($name !== null, fn($el) => $el->attr('name', $name)->attr('id', $name));
}

/**
 * Create a <select> tag
 * @return Html
 */
function select()
{
  return (new Html())->tag('select');
}

/**
 * Create an <option> tag
 * @param string|null $value Value attribute
 * @param string|null $label Option label/text
 * @return Html
 */
function option($value = null, $label = null)
{
  return (new Html())
    ->tag('option')
    ->when($value !== null, fn($el) => $el->attr('value', $value))
    ->when($label !== null, fn($el) => $el->append($label));
}

/**
 * Create a <textarea> tag
 * @param string|null $name Name and id attributes
 * @return Html
 */
function textarea($name = null)
{
  return (new Html())
    ->tag('textarea')
    ->when($name !== null, fn($el) => $el->attr('name', $name)->attr('id', $name));
}

/**
 * Create a <label> tag
 * @param string|null $for For attribute (element id)
 * @return Html
 */
function label($for = null)
{
  return (new Html())
    ->tag('label')
    ->when($for !== null, fn($el) => $el->attr('for', $for));
}

/**
 * Create a <form> tag
 * @param string|null $action Form action URL
 * @param string $method Form method (get, post)
 * @return Html
 */
function form($action = null, $method = 'post')
{
  return (new Html())
    ->tag('form')
    ->when($action !== null, fn($el) => $el->attr('action', $action))
    ->attr('method', $method);
}

/**
 * Create a <ul> tag
 * @return Html
 */
function ul()
{
  return (new Html())->tag('ul');
}

/**
 * Create an <ol> tag
 * @return Html
 */
function ol()
{
  return (new Html())->tag('ol');
}

/**
 * Create a <li> tag
 * @return Html
 */
function li()
{
  return (new Html())->tag('li');
}

/**
 * Create a <table> tag
 * @return Html
 */
function table()
{
  return (new Html())->tag('table');
}

/**
 * Create a <thead> tag
 * @return Html
 */
function thead()
{
  return (new Html())->tag('thead');
}

/**
 * Create a <tbody> tag
 * @return Html
 */
function tbody()
{
  return (new Html())->tag('tbody');
}

/**
 * Create a <tr> tag
 * @return Html
 */
function tr()
{
  return (new Html())->tag('tr');
}

/**
 * Create a <th> tag
 * @return Html
 */
function th()
{
  return (new Html())->tag('th');
}

/**
 * Create a <td> tag
 * @return Html
 */
function td()
{
  return (new Html())->tag('td');
}

/**
 * Create an <img> tag
 * @param string|null $src Image source URL
 * @param string|null $alt Alt text
 * @return Html
 */
function img($src = null, $alt = null)
{
  return (new Html())
    ->tag('img')
    ->when($src !== null, fn($el) => $el->attr('src', $src))
    ->when($alt !== null, fn($el) => $el->attr('alt', $alt));
}

/**
 * Create a <br> tag
 * @return Html
 */
function br()
{
  return (new Html())->tag('br');
}

/**
 * Create an <hr> tag
 * @return Html
 */
function hr()
{
  return (new Html())->tag('hr');
}

// Configuration helpers (return Closure for use with apply())

/**
 * Configuration helper for external links
 * Sets href, target="_blank", and rel="noopener noreferrer"
 * @param string $url URL to link to
 * @return \Closure
 */
function externalLink($url)
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
function selectOptions(array $options, $selected = [])
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
