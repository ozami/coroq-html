<?php
namespace Coroq\Html;

class NoEscape implements \Coroq\HtmlInterface
{
  public function __construct($text)
  {
    $this->_text = (string)$text;
  }
  
  public function __toString()
  {
    return $this->_text;
  }
}
