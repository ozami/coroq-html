<?php
declare(strict_types=1);

namespace Coroq\Html\Tag;

use Coroq\Html\Html;

class Input extends Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("input");
  }
  
  public function type($type): self
  {
    return $this->attr("type", $type);
  }

  public function name($name): self
  {
    return $this->attr("name", $name);
  }

  public function value($value): self
  {
    return $this->attr("value", $value);
  }

  public function checked(bool $checked = true): self
  {
    return $this->attr("checked", $checked);
  }
}
