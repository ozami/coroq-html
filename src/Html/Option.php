<?php
namespace Coroq\Html;

class Option extends \Coroq\Html
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
  
  public function label($label)
  {
    return $this->children([$label]);
  }
  
  public function value($value)
  {
    return $this->attr("value", $value);
  }
  
  public function selected($selected = true)
  {
    return $this->attr("selected", $selected);
  }
}
