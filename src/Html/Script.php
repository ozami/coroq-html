<?php
namespace Coroq\Html;

class Script extends \Coroq\Html
{
  public function __construct()
  {
    parent::__construct();
    $this->tag("script");
  }
  
  public function src($src)
  {
    return $this->attr("src", $src);
  }
  
  public function bridge($name, $value)
  {
    if (!preg_match("#^[a-zA-Z_][a-zA-Z0-9_]*$#", $name)) {
      throw new \InvalidArgumentException();
    }
    $encoded = base64_encode(json_encode($value, JSON_UNESCAPED_UNICODE));
    $code = "var $name = JSON.parse(atob('$encoded'));";
    return $this->append($code);
  }
}
