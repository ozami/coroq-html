<?php
namespace Coroq\Html\Tag;

use Coroq\Html\HtmlInterface;

class NoEscape implements HtmlInterface
{
  public $_text;
  
  public function __construct($text)
  {
    $this->_text = (string)$text;
  }
  
  public function __toString()
  {
    return $this->_text;
  }
}
