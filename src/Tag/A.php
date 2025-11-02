<?php
namespace Coroq\Html\Tag;

use Coroq\Html\Html;

class A extends Html
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
  
  public function href($href): self
  {
    return $this->attr("href", $href);
  }

  public function target($target): self
  {
    return $this->attr("target", $target);
  }

  public function autoTargetBlank(): self
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
