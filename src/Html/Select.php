<?php
namespace Coroq\Html;

class Select extends \Coroq\Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("select");
  }
  
  public function appendOptions(array $options, $selected = [])
  {
    $selected = (array)$selected;
    foreach ($options as $value => $label) {
      $option = new Option($label, $value);
      if (in_array("$value", $selected)) {
        $option->attr("selected", "");
      }
      $this->append($option);
    }
    return $this;
  }
}
