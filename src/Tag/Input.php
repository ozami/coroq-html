<?php
namespace Coroq\Html\Tag;

use Coroq\Html\Html;

class Input extends Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("input");
  }
  
  public function type($type)
  {
    return $this->attr("type", $type);
  }
  
  public function name($name)
  {
    return $this->attr("name", $name);
  }

  public function value($value)
  {
    return $this->attr("value", $value);
  }

  public function checked($checked = true)
  {
    return $this->attr("checked", $checked);
  }
}
