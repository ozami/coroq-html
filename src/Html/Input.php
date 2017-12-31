<?php
namespace Coroq\Html;

class Input extends \Coroq\Html
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
  
  public function chacked($checked = true)
  {
    return $this->attr("checked", $checked);
  }
}
