<?php
declare(strict_types=1);

namespace Coroq\Html;

use Coroq\Html\HtmlInterface;

class NoEscape implements HtmlInterface
{
  private string $text;

  public function __construct($text)
  {
    $this->text = (string)$text;
  }

  public function __toString(): string
  {
    return $this->text;
  }
}
