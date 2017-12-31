<?php
namespace Coroq\Html;

class A extends \Coroq\Html
{
  public function __construct($href = null, $target = null)
  {
    parent::__construct();
    $this->tag("a");
    if ($href !== null) {
      $this->attr("href", $href);
    }
    if ($target !== null) {
      $this->attr("target", $target);
    }
  }
  
  public function href($href)
  {
    return $this->attr("href", $href);
  }
  
  public function target($target)
  {
    return $this->attr("target", $target);
  }
  
  public function autoTargetBlank()
  {
    $href = $this->getAttr("href");
    if ($href == "") {
      return $this;
    }
    if (!preg_match("#^https?:#", $href)) {
      return $this;
    }
    return $this->attr("target", "_blank");
  }
}
