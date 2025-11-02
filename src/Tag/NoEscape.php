<?php
namespace Coroq\Html\Tag;

use Coroq\Html\HtmlInterface;

class NoEscape implements HtmlInterface
{
  /** @var string */
  public $_text;

  public function __construct($text)
  {
    $this->_text = (string)$text;
  }

  public function __toString(): string
  {
    return $this->_text;
  }
}
