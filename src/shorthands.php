<?php
namespace Coroq\Html;

use Coroq\Html\Html;

/**
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
 * @param mixed $html
 * @return void
 */
function p($html): void
{
  echo h($html);
}
