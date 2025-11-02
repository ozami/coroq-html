<?php
namespace Coroq\Html\Tag;

use Coroq\Html\Html;

class Option extends Html
{
  public function __construct($label = null, $value = null)
  {
    parent::__construct();
    $this->tag("option");
    if ($label !== null) {
      $this->append($label);
    }
    if ($value !== null) {
      $this->attr("value", $value);
    }
  }
  
  public function label($label): self
  {
    return $this->children([$label]);
  }

  public function value($value): self
  {
    return $this->attr("value", $value);
  }

  public function selected(bool $selected = true): self
  {
    return $this->attr("selected", $selected);
  }
}
